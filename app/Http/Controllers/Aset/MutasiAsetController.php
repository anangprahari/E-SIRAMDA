<?php

namespace App\Http\Controllers\Aset;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\MutasiAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MutasiAsetController extends Controller
{
    /**
     * Display listing of mutations with search & filter
     */
    public function index(Request $request)
    {
        $query = MutasiAset::with(['aset', 'user'])
            ->latest('tanggal_mutasi');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhereHas('aset', function ($q) use ($search) {
                        $q->where('nama_jenis_barang', 'like', "%{$search}%")
                            ->orWhere('kode_barang', 'like', "%{$search}%")
                            ->orWhere('register', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by ruangan
        if ($request->filled('ruangan')) {
            $query->where(function ($q) use ($request) {
                $q->where('ruangan_asal', $request->ruangan)
                    ->orWhere('ruangan_tujuan', $request->ruangan);
            });
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_mutasi', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_mutasi', '<=', $request->tanggal_sampai);
        }

        $perPage = (int) $request->get('per_page', 10);

        $mutasi = $query
            ->paginate($perPage)
            ->withQueryString()
            ->onEachSide(1);


        return view('mutasi.index', [
            'mutasi' => $mutasi,
            'ruanganOptions' => MutasiAset::ruanganOptions()
        ]);
    }

    /**
     * Show form to create new mutation
     * UPDATED: Tidak perlu generate nomor surat otomatis
     */
    public function create()
    {
        $assets = Aset::whereNotNull('ruangan')
            ->orderBy('nama_jenis_barang')
            ->get(['id', 'kode_barang', 'nama_jenis_barang', 'register', 'ruangan']);
        return view('mutasi.create', [
            'assets' => $assets,  // Changed from 'aset' to 'assets' for clarity
            'ruanganOptions' => MutasiAset::ruanganOptions()
        ]);
    }

    /**
     * Store new mutation
     * UPDATED: Validasi + Update lokasi_barang
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'aset_id' => 'required|exists:asets,id',
            'ruangan_tujuan' => 'required|string|in:' . implode(',', MutasiAset::ruanganOptions()),
            'lokasi_tujuan' => 'nullable|string|max:255',  // TAMBAHAN BARU
            'tanggal_mutasi' => 'required|date|before_or_equal:today',
            'nomor_surat' => 'required|string|max:100|unique:mutasi_asets,nomor_surat',
            'berita_acara' => 'required|file|mimes:pdf|max:10240',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'aset_id.required' => 'Aset harus dipilih.',
            'aset_id.exists' => 'Aset tidak ditemukan.',
            'ruangan_tujuan.required' => 'Ruangan tujuan harus dipilih.',
            'ruangan_tujuan.in' => 'Ruangan tujuan tidak valid.',
            'lokasi_tujuan.max' => 'Lokasi tujuan maksimal 255 karakter.',
            'tanggal_mutasi.required' => 'Tanggal mutasi harus diisi.',
            'tanggal_mutasi.before_or_equal' => 'Tanggal mutasi tidak boleh melebihi hari ini.',
            'nomor_surat.required' => 'Nomor surat harus diisi.',
            'nomor_surat.unique' => 'Nomor surat sudah digunakan.',
            'berita_acara.required' => 'Berita acara harus diupload.',
            'berita_acara.mimes' => 'Berita acara harus berformat PDF.',
            'berita_acara.max' => 'Ukuran berita acara maksimal 10MB.',
        ]);

        try {
            return DB::transaction(function () use ($validated, $request) {
                $aset = Aset::findOrFail($validated['aset_id']);

                // Validasi ruangan tidak sama
                if ($aset->ruangan === $validated['ruangan_tujuan']) {
                    return back()
                        ->with('error', 'Ruangan tujuan tidak boleh sama dengan ruangan asal.')
                        ->withInput();
                }

                // Upload berita acara
                $beritaAcaraPath = $this->uploadBeritaAcara($request->file('berita_acara'));

                // Create mutasi record dengan lokasi
                $mutasi = MutasiAset::create([
                    'aset_id' => $validated['aset_id'],
                    'user_id' => Auth::id(),
                    'ruangan_asal' => $aset->ruangan,
                    'ruangan_tujuan' => $validated['ruangan_tujuan'],
                    'lokasi_asal' => $aset->lokasi_barang,           // TAMBAHAN
                    'lokasi_tujuan' => $validated['lokasi_tujuan'],  // TAMBAHAN
                    'tanggal_mutasi' => $validated['tanggal_mutasi'],
                    'nomor_surat' => $validated['nomor_surat'],
                    'berita_acara_path' => $beritaAcaraPath,
                    'keterangan' => $validated['keterangan'] ?? null,
                ]);

                // Update aset: ruangan & lokasi_barang
                $updateData = [
                    'ruangan' => $validated['ruangan_tujuan']
                ];

                // Update lokasi jika diisi
                if (!empty($validated['lokasi_tujuan'])) {
                    $updateData['lokasi_barang'] = $validated['lokasi_tujuan'];
                }

                $aset->update($updateData);

                Log::info('Mutasi Created', [
                    'mutasi_id' => $mutasi->id,
                    'aset_id' => $aset->id,
                    'user_id' => Auth::id()
                ]);

                return redirect()->route('mutasi.show', $mutasi->id)
                    ->with('success', 'Mutasi aset berhasil disimpan dan aset telah dipindahkan.');
            });
        } catch (\Exception $e) {
            Log::error('Mutasi Store Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Gagal menyimpan mutasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show detail mutation
     */
    public function show(MutasiAset $mutasi)
    {
        $mutasi->load(['aset', 'user']);

        return view('mutasi.show', [
            'mutasi' => $mutasi,
            'ruanganOptions' => MutasiAset::ruanganOptions()
        ]);
    }

    /**
     * Display mutation history with filters
     */
    public function riwayat(Request $request)
    {
        $query = MutasiAset::with(['aset', 'user'])
            ->latest('tanggal_mutasi');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhereHas('aset', function ($q) use ($search) {
                        $q->where('nama_jenis_barang', 'like', "%{$search}%")
                            ->orWhere('kode_barang', 'like', "%{$search}%")
                            ->orWhere('register', 'like', "%{$search}%");
                    });
            });
        }

        // Filter ruangan
        if ($request->filled('ruangan')) {
            $query->where(function ($q) use ($request) {
                $q->where('ruangan_asal', $request->ruangan)
                    ->orWhere('ruangan_tujuan', $request->ruangan);
            });
        }

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_mutasi', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_mutasi', '<=', $request->tanggal_sampai);
        }

        $perPage = (int) $request->get('per_page', 20);

        $riwayat = $query
            ->paginate($perPage)
            ->withQueryString()
            ->onEachSide(1);


        return view('mutasi.riwayat', [
            'riwayat' => $riwayat,
            'ruanganOptions' => MutasiAset::ruanganOptions()
        ]);
    }

    /**
     * Display mutation history for specific asset
     */
    public function riwayatAset(Aset $aset)
    {
        $riwayat = $aset->mutasi()
            ->with(['user'])
            ->latest('tanggal_mutasi')
            ->paginate(4);

        return view('mutasi.riwayat-aset', [
            'aset' => $aset,
            'riwayat' => $riwayat,
            'ruanganOptions' => MutasiAset::ruanganOptions()
        ]);
    }

    /**
     * API: Search asset for mutation
     * UPDATED: Simple JSON untuk native autocomplete
     */
    public function cariAset(Request $request)
    {
        $query = Aset::whereNotNull('ruangan');

        if ($request->filled('q') || $request->filled('search')) {
            $search = $request->q ?? $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_jenis_barang', 'like', "%{$search}%")
                    ->orWhere('kode_barang', 'like', "%{$search}%")
                    ->orWhere('register', 'like', "%{$search}%");
            });
        }

        $aset = $query->limit(50)->get();

        // Return simple array untuk native autocomplete
        return response()->json([
            'success' => true,
            'data' => $aset->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode_barang' => $item->kode_barang,
                    'register' => $item->register,
                    'nama_jenis_barang' => $item->nama_jenis_barang,
                    'ruangan' => $item->ruangan,
                    'lokasi_barang' => $item->lokasi_barang,
                    'display_text' => $item->kode_barang . ' - ' . $item->nama_jenis_barang . ' (Reg: ' . $item->register . ')'
                ];
            })->values()
        ]);
    }

    /**
     * Upload berita acara file
     */
    private function uploadBeritaAcara($file)
    {
        $filename = 'berita-acara-' . time() . '-' . Str::random(10) . '.pdf';
        $path = $file->storeAs('berita-acara', $filename, 'public');
        return $path;
    }

    public function downloadBeritaAcara(MutasiAset $mutasi)
    {
        if (!$mutasi->berita_acara_path) {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = storage_path('app/public/' . $mutasi->berita_acara_path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan di penyimpanan');
        }

        $downloadName = 'Berita-Acara-' . str_replace(['/', '\\'], '-', $mutasi->nomor_surat) . '.pdf';

        return response()->download($filePath, $downloadName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function previewBeritaAcara(MutasiAset $mutasi)
    {
        if (!$mutasi->berita_acara_path) {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = storage_path('app/public/' . $mutasi->berita_acara_path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan di penyimpanan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline'
        ]);
    }
}

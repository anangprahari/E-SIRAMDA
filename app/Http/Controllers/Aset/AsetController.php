<?php

namespace App\Http\Controllers\Aset;

use App\Http\Controllers\Controller;
use App\Models\{Akun, Kelompok, Jenis, Objek, RincianObjek, SubRincianObjek, SubSubRincianObjek, Aset};
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Http\Response;
use App\Exports\AsetExport;
use Maatwebsite\Excel\Facades\Excel;


class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // ===============================
        // Entries per page (BARU)
        // ===============================
        $perPage = $request->integer('per_page', 20);
        $allowedPerPage = [10, 20, 30, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 20;
        }

        $query = Aset::with([
            'subSubRincianObjek.subRincianObjek.rincianObjek.objek.jenis.kelompok.akun'
        ]);

        // Mesin pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_bidang_barang', 'like', "%{$search}%")
                    ->orWhere('nama_jenis_barang', 'like', "%{$search}%")
                    ->orWhere('kode_barang', 'like', "%{$search}%")
                    ->orWhere('register', 'like', "%{$search}%");
            });
        }

        // Filter tahun perolehan (single year - untuk backward compatibility)
        if ($request->filled('tahun_perolehan')) {
            $query->where('tahun_perolehan', $request->tahun_perolehan);
        }

        // Filter rentang tahun perolehan (BARU)
        if ($request->filled('tahun_dari') || $request->filled('tahun_sampai')) {
            $query->where(function ($q) use ($request) {
                if ($request->filled('tahun_dari') && $request->filled('tahun_sampai')) {
                    // Jika kedua tahun diisi
                    $q->whereBetween('tahun_perolehan', [$request->tahun_dari, $request->tahun_sampai]);
                } elseif ($request->filled('tahun_dari')) {
                    // Jika hanya tahun dari yang diisi
                    $q->where('tahun_perolehan', '>=', $request->tahun_dari);
                } elseif ($request->filled('tahun_sampai')) {
                    // Jika hanya tahun sampai yang diisi
                    $q->where('tahun_perolehan', '<=', $request->tahun_sampai);
                }
            });
        }

        // Filter keadaan barang
        if ($request->filled('keadaan_barang')) {
            $query->where('keadaan_barang', $request->keadaan_barang);
        }

        $asets = $query->orderByRaw('
        CAST(SUBSTRING_INDEX(kode_barang, ".", 1) AS UNSIGNED),
        CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 2), ".", -1) AS UNSIGNED),
        CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 3), ".", -1) AS UNSIGNED),
        CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 4), ".", -1) AS UNSIGNED),  
        CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 5), ".", -1) AS UNSIGNED),
        CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 6), ".", -1) AS UNSIGNED),
        CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 7), ".", -1) AS UNSIGNED),
        CAST(SUBSTRING_INDEX(kode_barang, ".", -1) AS UNSIGNED)
    ')->paginate($perPage)
            ->withQueryString()
            ->onEachSide(1);


        return view('asets.index', compact('asets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $akuns = Akun::orderByRaw('CAST(kode AS UNSIGNED) ASC')->get();
        return view('asets.create', compact('akuns'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'akun_id' => 'required|exists:akuns,id',
            'kelompok_id' => 'required|exists:kelompoks,id',
            'jenis_id' => 'required|exists:jenis,id',
            'objek_id' => 'required|exists:objeks,id',
            'rincian_objek_id' => 'required|exists:rincian_objeks,id',
            'sub_rincian_objek_id' => 'required|exists:sub_rincian_objeks,id',
            'sub_sub_rincian_objek_id' => 'required|exists:sub_sub_rincian_objeks,id',
            'nama_bidang_barang' => 'required|string|max:255',
            'register' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    // PERBAIKAN: Untuk aset rusak berat, skip validasi uniqueness
                    if ($request->keadaan_barang === 'RB') {
                        return; // Skip validasi untuk rusak berat
                    }

                    // Validasi unique untuk kombinasi kode_barang + register (hanya untuk non-rusak berat)
                    if (Aset::where('kode_barang', $request->kode_barang)->where('register', $value)->exists()) {
                        $fail('Kombinasi kode barang dan register sudah digunakan.');
                    }
                }
            ],
            'nama_jenis_barang' => 'required|string|max:255',
            'merk_type' => 'nullable|string|max:255',
            'no_sertifikat' => 'nullable|string|max:255',
            'no_plat_kendaraan' => 'nullable|string|max:255',
            'no_pabrik' => 'nullable|string|max:255',
            'no_casis' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'asal_perolehan' => 'required|string|max:255',
            'tahun_perolehan' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'ukuran_barang_konstruksi' => 'nullable|string|max:255',
            'satuan' => 'required|string|max:100',
            'keadaan_barang' => ['required', Rule::in(['B', 'KB', 'RB'])],
            'jumlah_barang' => 'required|integer|min:1|max:100',
            'harga_satuan' => 'required|numeric|min:0',
            'bukti_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bukti_berita' => 'nullable|mimes:pdf|max:10240',
            'lokasi_barang' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'ruangan' => 'nullable|string|max:255',
            'kode_barang' => 'required|string', // Hapus validasi unique dari sini
        ]);

        try {
            return DB::transaction(function () use ($validated, $request) {
                // Handle file uploads
                $buktiBarangFileName = null;
                $buktiBeritaFileName = null;

                if ($request->hasFile('bukti_barang')) {
                    $buktiBarangFileName = 'bukti_barang_' . time() . '.' . $request->file('bukti_barang')->extension();
                    $request->file('bukti_barang')->storeAs('bukti_barang', $buktiBarangFileName, 'public');
                }

                if ($request->hasFile('bukti_berita')) {
                    $buktiBeritaFileName = 'bukti_berita_' . time() . '.' . $request->file('bukti_berita')->extension();
                    $request->file('bukti_berita')->storeAs('bukti_berita', $buktiBeritaFileName, 'public');
                }

                // **PERBAIKAN: Tentukan kode barang berdasarkan keadaan barang**
                $finalKodeBarang = $validated['keadaan_barang'] === 'RB'
                    ? $this->generateKodeBarangRusakBerat()
                    : $validated['kode_barang'];

                // Prepare base data
                $baseData = [
                    'sub_sub_rincian_objek_id' => $validated['sub_sub_rincian_objek_id'],
                    'nama_bidang_barang' => $validated['nama_bidang_barang'],
                    'nama_jenis_barang' => $validated['nama_jenis_barang'],
                    'merk_type' => $validated['merk_type'],
                    'no_sertifikat' => $validated['no_sertifikat'],
                    'no_plat_kendaraan' => $validated['no_plat_kendaraan'],
                    'no_pabrik' => $validated['no_pabrik'],
                    'no_casis' => $validated['no_casis'],
                    'bahan' => $validated['bahan'],
                    'asal_perolehan' => $validated['asal_perolehan'],
                    'tahun_perolehan' => $validated['tahun_perolehan'],
                    'ukuran_barang_konstruksi' => $validated['ukuran_barang_konstruksi'],
                    'satuan' => $validated['satuan'],
                    'keadaan_barang' => $validated['keadaan_barang'],
                    'jumlah_barang' => 1,
                    'harga_satuan' => $validated['harga_satuan'],
                    'lokasi_barang' => $validated['lokasi_barang'] ?? null,
                    'keterangan' => $validated['keterangan'] ?? null,
                    'ruangan' => $validated['ruangan'] ?? null,
                ];

                $jumlahBarang = (int)$validated['jumlah_barang'];
                $baseRegister = $validated['register'];
                $createdAssets = [];

                // **PERBAIKAN: Cari register terakhir untuk kode barang yang akan digunakan (final)**
                $lastRegisterNumber = $this->getLastRegisterNumber($finalKodeBarang);
                $startingNumber = $lastRegisterNumber + 1;

                // Buat multiple assets berdasarkan jumlah_barang
                for ($i = 0; $i < $jumlahBarang; $i++) {
                    $assetData = $baseData;
                    $currentNumber = $startingNumber + $i;
                    $sequence = str_pad($currentNumber, 3, '0', STR_PAD_LEFT);

                    // Generate register berurutan
                    $assetData['register'] = $this->generateRegisterFromKodeBarang($finalKodeBarang, $sequence);

                    // **PERBAIKAN: Gunakan kode barang yang sudah ditentukan (normal atau rusak berat)**
                    $assetData['kode_barang'] = $finalKodeBarang;

                    // Attachment files
                    $assetData['bukti_barang'] = $buktiBarangFileName;
                    $assetData['bukti_berita'] = $buktiBeritaFileName;

                    $createdAsset = Aset::create($assetData);
                    $createdAssets[] = $createdAsset->register;
                }

                $message = $jumlahBarang > 1
                    ? "Berhasil menambahkan {$jumlahBarang} aset. Register: " . implode(', ', array_slice($createdAssets, 0, 3)) .
                    ($jumlahBarang > 3 ? ' dan ' . ($jumlahBarang - 3) . ' lainnya' : '')
                    : "Aset berhasil ditambahkan dengan register: {$createdAssets[0]}";

                // **PERBAIKAN: Tambahkan informasi jika status rusak berat**
                if ($validated['keadaan_barang'] === 'RB') {
                    $message .= ". Kode barang telah diubah ke kategori Rusak Berat: {$finalKodeBarang}";
                }

                return redirect()->route('asets.index')->with('success', $message);
            });
        } catch (\Exception $e) {
            Log::error('Error creating aset: ' . $e->getMessage(), [
                'request' => $validated,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data aset: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mendapatkan nomor register terakhir untuk kode barang tertentu
     */
    private function getLastRegisterNumber(string $kodeBarang): int
    {
        // Cari aset dengan kode barang yang sama, urutkan berdasarkan register terakhir
        $lastAsset = Aset::where('kode_barang', $kodeBarang)
            ->orderByRaw('CAST(register AS UNSIGNED) DESC')
            ->first();

        if (!$lastAsset) {
            return 0; // Jika belum ada, mulai dari 0
        }

        // Ambil nomor register dan convert ke integer
        $lastNumber = (int) $lastAsset->register;

        return $lastNumber;
    }

    /**
     * Generate register dari kode barang dengan menambahkan sequence number
     */
    private function generateRegisterFromKodeBarang(string $kodeBarang, string $sequence): string
    {
        return $sequence;
    }

    /**
     * Generate register dengan format yang benar untuk preview
     */
    public function generateRegisterPreview(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'kode_barang' => 'required|string',
                'keadaan_barang' => 'nullable|string'
            ]);

            $kodeBarang = $request->kode_barang;
            $keadaanBarang = $request->keadaan_barang;

            // PERBAIKAN: Untuk aset rusak berat, gunakan kode barang rusak berat
            if ($keadaanBarang === 'RB') {
                $kodeBarang = $this->generateKodeBarangRusakBerat();
            }

            // Cari nomor register terakhir untuk kode barang ini
            $lastRegisterNumber = $this->getLastRegisterNumber($kodeBarang);

            // Nomor register berikutnya
            $nextNumber = $lastRegisterNumber + 1;
            $sequence = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Generate register preview
            $registerPreview = $this->generateRegisterFromKodeBarang($kodeBarang, $sequence);

            $infoMessage = "Register terakhir untuk kode barang {$kodeBarang}: " .
                ($lastRegisterNumber > 0 ? str_pad($lastRegisterNumber, 3, '0', STR_PAD_LEFT) : 'Belum ada');

            // PERBAIKAN: Tambahkan info khusus untuk rusak berat
            if ($keadaanBarang === 'RB') {
                $infoMessage .= " (Kategori: Rusak Berat - register boleh duplikat)";
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'register_preview' => $registerPreview,
                    'last_number' => $lastRegisterNumber,
                    'next_number' => $nextNumber,
                    'sequence' => $sequence,
                    'kode_barang_final' => $kodeBarang, // Tambahkan ini untuk frontend
                    'info' => $infoMessage
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating register preview: ' . $e->getMessage(), [
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat generate register preview.'
            ], 500);
        }
    }

    /**
     * Mendapatkan informasi register untuk kode barang tertentu
     */
    public function getRegisterInfo(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'kode_barang' => 'required|string'
            ]);

            $kodeBarang = $request->kode_barang;

            // Hitung total aset dengan kode barang yang sama
            $totalAssets = Aset::where('kode_barang', $kodeBarang)->count();

            // Cari nomor register terakhir
            $lastRegisterNumber = $this->getLastRegisterNumber($kodeBarang);

            // Nomor register berikutnya
            $nextNumber = $lastRegisterNumber + 1;

            return response()->json([
                'success' => true,
                'data' => [
                    'kode_barang' => $kodeBarang,
                    'total_existing_assets' => $totalAssets,
                    'last_register_number' => $lastRegisterNumber,
                    'next_register_number' => $nextNumber,
                    'next_register_formatted' => str_pad($nextNumber, 3, '0', STR_PAD_LEFT),
                    'info_message' => $totalAssets > 0
                        ? "Sudah ada {$totalAssets} aset dengan kode ini. Register berikutnya: " . str_pad($nextNumber, 3, '0', STR_PAD_LEFT)
                        : "Belum ada aset dengan kode ini. Register akan dimulai dari: 001"
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting register info: ' . $e->getMessage(), [
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil informasi register.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Aset $aset): View
    {
        $aset->load(['subSubRincianObjek.subRincianObjek.rincianObjek.objek.jenis.kelompok.akun']);
        return view('asets.show', compact('aset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aset $aset): View
    {
        // Load semua relasi terlebih dahulu
        $aset->load(['subSubRincianObjek.subRincianObjek.rincianObjek.objek.jenis.kelompok.akun']);

        // Extract hierarchy untuk form
        $hierarchy = $this->extractHierarchy($aset);

        // Get all dropdown data based on current selections
        $akuns = Akun::orderByRaw('CAST(kode AS UNSIGNED) ASC')->get();
        $kelompoks = Kelompok::where('akun_id', $hierarchy['akun']->id)
            ->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get();
        $jenis = Jenis::where('kelompok_id', $hierarchy['kelompok']->id)
            ->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get();
        $objeks = Objek::where('jenis_id', $hierarchy['jenis']->id)
            ->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get();
        $rincianObjeks = RincianObjek::where('objek_id', $hierarchy['objek']->id)
            ->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get();
        $subRincianObjeks = SubRincianObjek::where('rincian_objek_id', $hierarchy['rincianObjek']->id)
            ->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get();
        $subSubRincianObjeks = SubSubRincianObjek::where('sub_rincian_objek_id', $hierarchy['subRincianObjek']->id)
            ->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get();

        // Tambahkan selected values untuk memudahkan pre-populate form
        $selectedValues = [
            'akun_id' => $hierarchy['akun']->id,
            'kelompok_id' => $hierarchy['kelompok']->id,
            'jenis_id' => $hierarchy['jenis']->id,
            'objek_id' => $hierarchy['objek']->id,
            'rincian_objek_id' => $hierarchy['rincianObjek']->id,
            'sub_rincian_objek_id' => $hierarchy['subRincianObjek']->id,
            'sub_sub_rincian_objek_id' => $hierarchy['subSubRincianObjek']->id,
        ];

        return view('asets.edit', compact(
            'aset',
            'hierarchy',
            'akuns',
            'kelompoks',
            'jenis',
            'objeks',
            'rincianObjeks',
            'subRincianObjeks',
            'subSubRincianObjeks',
            'selectedValues'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    // Perbaikan method update() di AsetController.php
    public function update(Request $request, Aset $aset): RedirectResponse
    {
        Log::info('Update request received', [
            'aset_id' => $aset->id,
            'request_data' => $request->except(['bukti_barang', 'bukti_berita'])
        ]);

        $validated = $request->validate(
            [
                'akun_id' => 'required|exists:akuns,id',
                'kelompok_id' => 'required|exists:kelompoks,id',
                'jenis_id' => 'required|exists:jenis,id',
                'objek_id' => 'required|exists:objeks,id',
                'rincian_objek_id' => 'required|exists:rincian_objeks,id',
                'sub_rincian_objek_id' => 'required|exists:sub_rincian_objeks,id',
                'sub_sub_rincian_objek_id' => 'required|exists:sub_sub_rincian_objeks,id',
                'nama_bidang_barang' => 'required|string|max:255',
                'register' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($aset, $request) {
                        // PERBAIKAN: Untuk aset rusak berat, skip validasi uniqueness
                        if ($request->keadaan_barang === 'RB') {
                            return; // Skip validasi untuk rusak berat
                        }

                        // Validasi unique untuk kombinasi kode_barang + register (exclude current record)
                        $query = Aset::where('kode_barang', $request->kode_barang)->where('register', $value);

                        // Exclude current record
                        if ($aset->id) {
                            $query->where('id', '!=', $aset->id);
                        }

                        if ($query->exists()) {
                            $fail('Kombinasi kode barang dan register sudah digunakan.');
                        }
                    }
                ],
                'nama_jenis_barang' => 'required|string|max:255',
                'merk_type' => 'nullable|string|max:255',
                'no_sertifikat' => 'nullable|string|max:255',
                'no_plat_kendaraan' => 'nullable|string|max:255',
                'no_pabrik' => 'nullable|string|max:255',
                'no_casis' => 'nullable|string|max:255',
                'bahan' => 'nullable|string|max:255',
                'asal_perolehan' => 'required|string|max:255',
                'tahun_perolehan' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
                'ukuran_barang_konstruksi' => 'nullable|string|max:255',
                'satuan' => 'required|string|max:100',
                'keadaan_barang' => ['required', Rule::in(['B', 'KB', 'RB'])],
                'jumlah_barang' => 'required|integer|min:1|max:100',
                'harga_satuan' => 'required|numeric|min:0',
                'bukti_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'bukti_berita' => 'nullable|mimes:pdf|max:10240',
                'lokasi_barang' => 'nullable|string|max:255',
                'keterangan' => 'nullable|string',
                'ruangan' => 'nullable|string|max:255',
                'kode_barang' => 'required|string', // Hapus validasi unique dari sini
            ],
            [
                'akun_id.required' => 'Akun harus dipilih',
                'kelompok_id.required' => 'Kelompok harus dipilih',
                'jenis_id.required' => 'Jenis harus dipilih',
                'objek_id.required' => 'Objek harus dipilih',
                'rincian_objek_id.required' => 'Rincian objek harus dipilih',
                'sub_rincian_objek_id.required' => 'Sub rincian objek harus dipilih',
                'sub_sub_rincian_objek_id.required' => 'Sub sub rincian objek harus dipilih',
                'register.unique' => 'Register sudah digunakan',
                'kode_barang.unique' => 'Kode barang sudah digunakan',
                'tahun_perolehan.digits' => 'Tahun perolehan harus 4 digit',
                'tahun_perolehan.max' => 'Tahun perolehan tidak boleh melebihi tahun sekarang',
                'jumlah_barang.max' => 'Jumlah barang maksimal 100',
                'bukti_barang.max' => 'Ukuran file gambar maksimal 2MB',
                'bukti_berita.max' => 'Ukuran file PDF maksimal 10MB',
            ]
        );

        Log::info('Validation passed', ['validated_data' => array_keys($validated)]);

        try {
            return DB::transaction(function () use ($validated, $request, $aset) {
                $fileUpdates = [];

                // Handle bukti_barang upload
                if ($request->hasFile('bukti_barang')) {
                    $file = $request->file('bukti_barang');
                    if ($file->isValid()) {
                        if ($aset->bukti_barang && Storage::disk('public')->exists('bukti_barang/' . $aset->bukti_barang)) {
                            Storage::disk('public')->delete('bukti_barang/' . $aset->bukti_barang);
                            Log::info('Old bukti_barang deleted', ['filename' => $aset->bukti_barang]);
                        }

                        $fileName = 'bukti_barang_' . $aset->id . '_' . time() . '.' . $file->extension();
                        $file->storeAs('bukti_barang', $fileName, 'public');
                        $fileUpdates['bukti_barang'] = $fileName;
                        Log::info('Bukti barang uploaded', ['filename' => $fileName]);
                    } else {
                        Log::error('Invalid bukti_barang file');
                        throw new \Exception('File bukti barang tidak valid');
                    }
                }

                // Handle bukti_berita upload
                if ($request->hasFile('bukti_berita')) {
                    $file = $request->file('bukti_berita');
                    if ($file->isValid()) {
                        if ($aset->bukti_berita && Storage::disk('public')->exists('bukti_berita/' . $aset->bukti_berita)) {
                            Storage::disk('public')->delete('bukti_berita/' . $aset->bukti_berita);
                            Log::info('Old bukti_berita deleted', ['filename' => $aset->bukti_berita]);
                        }

                        $fileName = 'bukti_berita_' . $aset->id . '_' . time() . '.' . $file->extension();
                        $file->storeAs('bukti_berita', $fileName, 'public');
                        $fileUpdates['bukti_berita'] = $fileName;
                        Log::info('Bukti berita uploaded', ['filename' => $fileName]);
                    } else {
                        Log::error('Invalid bukti_berita file');
                        throw new \Exception('File bukti berita tidak valid');
                    }
                }

                // **PERBAIKAN: Tentukan kode barang berdasarkan keadaan barang**
                $finalKodeBarang = $validated['keadaan_barang'] === 'RB'
                    ? $this->generateKodeBarangRusakBerat()
                    : $validated['kode_barang'];

                // Prepare update data
                $updateData = [
                    'sub_sub_rincian_objek_id' => $validated['sub_sub_rincian_objek_id'],
                    'nama_bidang_barang' => $validated['nama_bidang_barang'],
                    'register' => $validated['register'],
                    'kode_barang' => $finalKodeBarang, // **PERBAIKAN: Gunakan kode barang yang sudah ditentukan**
                    'nama_jenis_barang' => $validated['nama_jenis_barang'],
                    'merk_type' => $validated['merk_type'],
                    'no_sertifikat' => $validated['no_sertifikat'],
                    'no_plat_kendaraan' => $validated['no_plat_kendaraan'],
                    'no_pabrik' => $validated['no_pabrik'],
                    'no_casis' => $validated['no_casis'],
                    'bahan' => $validated['bahan'],
                    'asal_perolehan' => $validated['asal_perolehan'],
                    'tahun_perolehan' => $validated['tahun_perolehan'],
                    'ukuran_barang_konstruksi' => $validated['ukuran_barang_konstruksi'],
                    'satuan' => $validated['satuan'],
                    'keadaan_barang' => $validated['keadaan_barang'],
                    'jumlah_barang' => $validated['jumlah_barang'],
                    'harga_satuan' => $validated['harga_satuan'],
                    'lokasi_barang' => $validated['lokasi_barang'] ?? null,
                    'keterangan' => $validated['keterangan'] ?? null,
                    'ruangan' => $validated['ruangan'] ?? null,
                ];

                // Merge file updates
                $updateData = array_merge($updateData, $fileUpdates);

                Log::info('Attempting to update asset', ['aset_id' => $aset->id, 'update_data' => $updateData]);

                $updated = $aset->update($updateData);

                if (!$updated) {
                    Log::error('Failed to update asset', ['aset_id' => $aset->id]);
                    throw new \Exception('Gagal mengupdate data aset');
                }

                Log::info('Asset updated successfully', ['aset_id' => $aset->id]);

                $message = 'Aset berhasil diperbarui';

                // **PERBAIKAN: Tambahkan informasi jika status rusak berat**
                if ($validated['keadaan_barang'] === 'RB' && $finalKodeBarang !== $validated['kode_barang']) {
                    $message .= ". Kode barang telah diubah ke kategori Rusak Berat: {$finalKodeBarang}";
                }

                return redirect()->route('asets.index')->with('success', $message);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in update', [
                'aset_id' => $aset->id,
                'errors' => $e->errors()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating aset', [
                'aset_id' => $aset->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data aset: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aset $aset): RedirectResponse
    {
        try {
            // Delete associated files
            if ($aset->bukti_barang) {
                Storage::disk('public')->delete('bukti_barang/' . $aset->bukti_barang);
            }
            if ($aset->bukti_berita) {
                Storage::disk('public')->delete('bukti_berita/' . $aset->bukti_berita);
            }

            $aset->delete();

            return redirect()->route('asets.index')
                ->with('success', 'Aset berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting aset: ' . $e->getMessage(), [
                'aset_id' => $aset->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus aset.');
        }
    }

    private function generateSequentialIdentifier($base, $sequence, $isKodeBarang = false)
    {
        if ($isKodeBarang) {
            // Untuk kode_barang, tambahkan sequence di akhir dengan separator
            return $base . '.' . $sequence;
        } else {
            // Untuk register, pastikan format 3 digit berurutan
            // Hapus suffix angka jika ada, lalu tambahkan sequence baru
            $cleanBase = preg_replace('/\d+$/', '', $base);
            return $cleanBase . $sequence;
        }
    }

    private function cleanRegisterNumber(string $register): string
    {
        // Hapus suffix angka 3 digit jika ada
        return preg_replace('/\d{3}$/', '', $register);
    }

    /**
     * Generate unique kode barang with sequence
     */
    private function generateUniqueRegister($baseRegister, $sequence)
    {
        $newRegister = $this->generateSequentialIdentifier($baseRegister, $sequence);

        // Cek apakah register sudah ada
        while (Aset::where('register', $newRegister)->exists()) {
            // Jika sudah ada, increment sequence
            $sequenceNum = (int)$sequence + 1;
            $sequence = str_pad($sequenceNum, 3, '0', STR_PAD_LEFT);
            $newRegister = $this->generateSequentialIdentifier($baseRegister, $sequence);
        }

        return $newRegister;
    }


    /**
     * Get asset hierarchy data for AJAX calls (used in edit form)
     */
    public function getAssetHierarchy(Aset $aset): JsonResponse
    {
        try {
            $hierarchy = $this->extractHierarchy($aset);

            return response()->json([
                'success' => true,
                'data' => [
                    'akun' => [
                        'id' => $hierarchy['akun']->id,
                        'nama' => $hierarchy['akun']->nama,
                        'kode' => $hierarchy['akun']->kode
                    ],
                    'kelompok' => [
                        'id' => $hierarchy['kelompok']->id,
                        'nama' => $hierarchy['kelompok']->nama,
                        'kode' => $hierarchy['kelompok']->kode
                    ],
                    'jenis' => [
                        'id' => $hierarchy['jenis']->id,
                        'nama' => $hierarchy['jenis']->nama,
                        'kode' => $hierarchy['jenis']->kode
                    ],
                    'objek' => [
                        'id' => $hierarchy['objek']->id,
                        'nama' => $hierarchy['objek']->nama,
                        'kode' => $hierarchy['objek']->kode
                    ],
                    'rincianObjek' => [
                        'id' => $hierarchy['rincianObjek']->id,
                        'nama' => $hierarchy['rincianObjek']->nama,
                        'kode' => $hierarchy['rincianObjek']->kode
                    ],
                    'subRincianObjek' => [
                        'id' => $hierarchy['subRincianObjek']->id,
                        'nama' => $hierarchy['subRincianObjek']->nama,
                        'kode' => $hierarchy['subRincianObjek']->kode
                    ],
                    'subSubRincianObjek' => [
                        'id' => $hierarchy['subSubRincianObjek']->id,
                        'nama_barang' => $hierarchy['subSubRincianObjek']->nama_barang,
                        'kode' => $hierarchy['subSubRincianObjek']->kode
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting asset hierarchy: ' . $e->getMessage(), ['aset_id' => $aset->id]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil hierarki data aset.'
            ], 500);
        }
    }

    /**
     * Get complete dropdown data for create/edit forms
     */
    public function getCompleteDropdownData(Request $request): JsonResponse
    {
        try {
            $data = [];

            // Always get akuns
            $data['akuns'] = Akun::orderBy('nama')->get(['id', 'nama', 'kode']);

            // Get kelompoks if akun_id provided
            if ($request->filled('akun_id')) {
                $data['kelompoks'] = Kelompok::where('akun_id', $request->akun_id)
                    ->orderBy('nama')->get(['id', 'nama', 'kode']);
            }

            // Get jenis if kelompok_id provided
            if ($request->filled('kelompok_id')) {
                $data['jenis'] = Jenis::where('kelompok_id', $request->kelompok_id)
                    ->orderBy('nama')->get(['id', 'nama', 'kode']);
            }

            // Get objeks if jenis_id provided
            if ($request->filled('jenis_id')) {
                $data['objeks'] = Objek::where('jenis_id', $request->jenis_id)
                    ->orderBy('nama')->get(['id', 'nama', 'kode']);
            }

            // Get rincian objeks if objek_id provided
            if ($request->filled('objek_id')) {
                $data['rincianObjeks'] = RincianObjek::where('objek_id', $request->objek_id)
                    ->orderBy('nama')->get(['id', 'nama', 'kode']);
            }

            // Get sub rincian objeks if rincian_objek_id provided
            if ($request->filled('rincian_objek_id')) {
                $data['subRincianObjeks'] = SubRincianObjek::where('rincian_objek_id', $request->rincian_objek_id)
                    ->orderBy('nama')->get(['id', 'nama', 'kode']);
            }

            // Get sub sub rincian objeks if sub_rincian_objek_id provided
            if ($request->filled('sub_rincian_objek_id')) {
                $data['subSubRincianObjeks'] = SubSubRincianObjek::where('sub_rincian_objek_id', $request->sub_rincian_objek_id)
                    ->orderBy('nama_barang')->get(['id', 'nama_barang', 'kode']);
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting complete dropdown data: ' . $e->getMessage(), [
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data dropdown.'
            ], 500);
        }
    }

    /**
     * Export assets to Excel
     */
    public function export(Request $request)
    {
        try {
            // Validate request parameters (UPDATE VALIDATION)
            $request->validate([
                'search' => 'nullable|string|max:255',
                'tahun_perolehan' => 'nullable|integer|min:1900|max:' . date('Y'),
                'tahun_dari' => 'nullable|integer|min:1900|max:' . date('Y'),
                'tahun_sampai' => 'nullable|integer|min:1900|max:' . date('Y'),
                'keadaan_barang' => 'nullable|string|in:B,KB,RB',
            ]);

            // Validasi rentang tahun
            if ($request->filled('tahun_dari') && $request->filled('tahun_sampai')) {
                if ($request->tahun_dari > $request->tahun_sampai) {
                    return redirect()->back()
                        ->with('error', 'Tahun dari tidak boleh lebih besar dari tahun sampai.');
                }
            }

            // Get filter parameters (same logic as index method)
            $query = Aset::with([
                'subSubRincianObjek.subRincianObjek.rincianObjek.objek.jenis.kelompok.akun'
            ]);

            // Apply same filters as index method
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_bidang_barang', 'like', "%{$search}%")
                        ->orWhere('nama_jenis_barang', 'like', "%{$search}%")
                        ->orWhere('kode_barang', 'like', "%{$search}%")
                        ->orWhere('register', 'like', "%{$search}%");
                });
            }

            // Filter tahun perolehan (single year - untuk backward compatibility)
            if ($request->filled('tahun_perolehan')) {
                $query->where('tahun_perolehan', $request->tahun_perolehan);
            }

            // Filter rentang tahun perolehan (BARU)
            if ($request->filled('tahun_dari') || $request->filled('tahun_sampai')) {
                $query->where(function ($q) use ($request) {
                    if ($request->filled('tahun_dari') && $request->filled('tahun_sampai')) {
                        // Jika kedua tahun diisi
                        $q->whereBetween('tahun_perolehan', [$request->tahun_dari, $request->tahun_sampai]);
                    } elseif ($request->filled('tahun_dari')) {
                        // Jika hanya tahun dari yang diisi
                        $q->where('tahun_perolehan', '>=', $request->tahun_dari);
                    } elseif ($request->filled('tahun_sampai')) {
                        // Jika hanya tahun sampai yang diisi
                        $q->where('tahun_perolehan', '<=', $request->tahun_sampai);
                    }
                });
            }

            // Filter keadaan barang
            if ($request->filled('keadaan_barang')) {
                $query->where('keadaan_barang', $request->keadaan_barang);
            }

            // PERBAIKAN: Terapkan pengurutan yang sama seperti di method index()
            $query->orderByRaw('
            CAST(SUBSTRING_INDEX(kode_barang, ".", 1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 2), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 3), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 4), ".", -1) AS UNSIGNED),  
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 5), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 6), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 7), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(kode_barang, ".", -1) AS UNSIGNED)
        ');

            $totalRecords = $query->count();

            if ($totalRecords === 0) {
                return redirect()->back()
                    ->with('warning', 'Tidak ada data untuk diekspor dengan filter yang dipilih.');
            }

            // Generate filename with filter info (UPDATE FILENAME GENERATION)
            $filename = 'Data_Aset_' . date('Y-m-d_H-i-s');

            $filterInfo = [];
            if ($request->filled('search')) $filterInfo[] = 'Search';
            if ($request->filled('tahun_perolehan')) $filterInfo[] = 'Tahun' . $request->tahun_perolehan;
            if ($request->filled('tahun_dari') && $request->filled('tahun_sampai')) {
                $filterInfo[] = 'Tahun' . $request->tahun_dari . '-' . $request->tahun_sampai;
            } elseif ($request->filled('tahun_dari')) {
                $filterInfo[] = 'Dari' . $request->tahun_dari;
            } elseif ($request->filled('tahun_sampai')) {
                $filterInfo[] = 'Sampai' . $request->tahun_sampai;
            }
            if ($request->filled('keadaan_barang')) $filterInfo[] = str_replace(' ', '', $request->keadaan_barang);

            if (!empty($filterInfo)) {
                $filename .= '_' . implode('_', $filterInfo);
            }
            $filename .= '.xlsx';

            // Log export activity (UPDATE LOG)
            Log::info('Exporting assets to Excel', [
                'user_id' => Auth::id() ?? 'system',
                'total_records' => $totalRecords,
                'filters' => [
                    'search' => $request->search,
                    'tahun_perolehan' => $request->tahun_perolehan,
                    'tahun_dari' => $request->tahun_dari,
                    'tahun_sampai' => $request->tahun_sampai,
                    'keadaan_barang' => $request->keadaan_barang
                ],
                'filename' => $filename
            ]);

            if ($totalRecords > 5000) {
                ini_set('memory_limit', '512M');
                set_time_limit(300);
            }

            return Excel::download(
                new AsetExport(
                    $request->search,
                    $request->tahun_perolehan,
                    $request->keadaan_barang,
                    $request->tahun_dari,
                    $request->tahun_sampai
                ),
                $filename,
                \Maatwebsite\Excel\Excel::XLSX
            );
        } catch (\Exception $e) {
            Log::error('Error exporting assets to Excel: ' . $e->getMessage(), [
                'user_id' => Auth::id() ?? 'system',
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengekspor data. Silakan coba lagi atau hubungi administrator.');
        }
    }

    // ===================================
    // DROPDOWN API METHODS
    // ===================================

    /**
     * Get kelompoks by akun_id
     */
    public function getKelompoks(int $akunId): JsonResponse
    {
        try {
            $kelompoks = Kelompok::where('akun_id', $akunId)
                ->orderByRaw('CAST(kode AS UNSIGNED) ASC')
                ->get(['id', 'nama', 'kode']);

            return response()->json([
                'success' => true,
                'data' => $kelompoks
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting kelompoks: ' . $e->getMessage(), ['akun_id' => $akunId]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kelompok.'
            ], 500);
        }
    }

    /**
     * Get jenis by kelompok_id
     */
    public function getJenis(int $kelompokId): JsonResponse
    {
        try {
            $jenis = Jenis::where('kelompok_id', $kelompokId)
                ->orderByRaw('CAST(kode AS UNSIGNED) ASC')
                ->get(['id', 'nama', 'kode']);

            return response()->json([
                'success' => true,
                'data' => $jenis
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting jenis: ' . $e->getMessage(), ['kelompok_id' => $kelompokId]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data jenis.'
            ], 500);
        }
    }

    /**
     * Get objeks by jenis_id
     */
    public function getObjeks(int $jenisId): JsonResponse
    {
        try {
            $objeks = Objek::where('jenis_id', $jenisId)
                ->orderByRaw('CAST(kode AS UNSIGNED) ASC')
                ->get(['id', 'nama', 'kode']);

            return response()->json([
                'success' => true,
                'data' => $objeks
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting objeks: ' . $e->getMessage(), ['jenis_id' => $jenisId]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data objek.'
            ], 500);
        }
    }

    /**
     * Get rincian objeks by objek_id
     */
    public function getRincianObjeks(int $objekId): JsonResponse
    {
        try {
            $rincianObjeks = RincianObjek::where('objek_id', $objekId)
                ->orderByRaw('CAST(kode AS UNSIGNED) ASC')
                ->get(['id', 'nama', 'kode']);

            return response()->json([
                'success' => true,
                'data' => $rincianObjeks
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting rincian objeks: ' . $e->getMessage(), ['objek_id' => $objekId]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data rincian objek.'
            ], 500);
        }
    }

    /**
     * Get sub rincian objeks by rincian_objek_id
     */
    public function getSubRincianObjeks(int $rincianObjekId): JsonResponse
    {
        try {
            $subRincianObjeks = SubRincianObjek::where('rincian_objek_id', $rincianObjekId)
                ->orderByRaw('CAST(kode AS UNSIGNED) ASC')
                ->get(['id', 'nama', 'kode']);

            return response()->json([
                'success' => true,
                'data' => $subRincianObjeks
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting sub rincian objeks: ' . $e->getMessage(), ['rincian_objek_id' => $rincianObjekId]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data sub rincian objek.'
            ], 500);
        }
    }

    /**
     * Get sub sub rincian objeks by sub_rincian_objek_id
     */
    public function getSubSubRincianObjeks(int $subRincianObjekId): JsonResponse
    {
        try {
            $subSubRincianObjeks = SubSubRincianObjek::where('sub_rincian_objek_id', $subRincianObjekId)
                ->orderByRaw('CAST(kode AS UNSIGNED) ASC')
                ->get(['id', 'nama_barang', 'kode']);

            return response()->json([
                'success' => true,
                'data' => $subSubRincianObjeks
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting sub sub rincian objeks: ' . $e->getMessage(), ['sub_rincian_objek_id' => $subRincianObjekId]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data sub sub rincian objek.'
            ], 500);
        }
    }

    /**
     * Generate kode barang preview for AJAX
     */
    public function generateKodeBarangPreview(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'sub_sub_rincian_objek_id' => 'required|exists:sub_sub_rincian_objeks,id'
            ]);

            $kodeBarang = $this->generateKodeBarang($request->sub_sub_rincian_objek_id);

            return response()->json([
                'success' => true,
                'kode_barang' => $kodeBarang
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating kode barang preview: ' . $e->getMessage(), [
                'sub_sub_rincian_objek_id' => $request->input('sub_sub_rincian_objek_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal generate kode barang: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===================================
    // PRIVATE HELPER METHODS
    // ===================================

    /**
     * Generate kode barang otomatis berdasarkan hierarki
     * Format: 1.2.5.01.05.01.01.001
     */
    /**
     * Generate kode barang automatically based on hierarchy
     */
    public function generateKodeBarang(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'akun_id' => 'required|exists:akuns,id',
                'kelompok_id' => 'required|exists:kelompoks,id',
                'jenis_id' => 'required|exists:jenis,id',
                'objek_id' => 'required|exists:objeks,id',
                'rincian_objek_id' => 'required|exists:rincian_objeks,id',
                'sub_rincian_objek_id' => 'required|exists:sub_rincian_objeks,id',
                'sub_sub_rincian_objek_id' => 'required|exists:sub_sub_rincian_objeks,id',
            ]);

            // Load all hierarchy data
            $akun = Akun::find($request->akun_id);
            $kelompok = Kelompok::find($request->kelompok_id);
            $jenis = Jenis::find($request->jenis_id);
            $objek = Objek::find($request->objek_id);
            $rincianObjek = RincianObjek::find($request->rincian_objek_id);
            $subRincianObjek = SubRincianObjek::find($request->sub_rincian_objek_id);
            $subSubRincianObjek = SubSubRincianObjek::find($request->sub_sub_rincian_objek_id);

            // Generate kode barang: AKUN.KELOMPOK.JENIS.OBJEK.RINCIAN.SUBRINCIAN.SUBSUBRINCIAN
            $kodeBarang = implode('.', [
                $akun->kode,
                $kelompok->kode,
                $jenis->kode,
                $objek->kode,
                $rincianObjek->kode,
                $subRincianObjek->kode,
                $subSubRincianObjek->kode
            ]);

            // Check if kode already exists, if yes add sequence number
            $originalKode = $kodeBarang;
            $sequence = 1;
            while (Aset::where('kode_barang', $kodeBarang)->exists()) {
                $kodeBarang = $originalKode . '.' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
                $sequence++;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'kode_barang' => $kodeBarang,
                    'hierarchy_info' => [
                        'akun' => $akun->nama,
                        'kelompok' => $kelompok->nama,
                        'jenis' => $jenis->nama,
                        'objek' => $objek->nama,
                        'rincian_objek' => $rincianObjek->nama,
                        'sub_rincian_objek' => $subRincianObjek->nama,
                        'sub_sub_rincian_objek' => $subSubRincianObjek->nama_barang
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating kode barang: ' . $e->getMessage(), [
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat generate kode barang.'
            ], 500);
        }
    }

    /**
     * Check if kode barang is unique (for validation)
     */
    public function checkKodeBarangUnique(Request $request): JsonResponse
    {
        try {
            $kodeBarang = $request->get('kode_barang');
            $excludeId = $request->get('exclude_id'); // For edit form

            if (!$kodeBarang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode barang harus diisi.'
                ]);
            }

            $query = Aset::where('kode_barang', $kodeBarang);

            // Exclude current record if editing
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            $exists = $query->exists();

            return response()->json([
                'success' => true,
                'data' => [
                    'is_unique' => !$exists,
                    'exists' => $exists,
                    'message' => $exists ? 'Kode barang sudah digunakan.' : 'Kode barang tersedia.'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking kode barang uniqueness: ' . $e->getMessage(), [
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat validasi kode barang.'
            ], 500);
        }
    }

    /**
     * Check if register is unique (for validation)
     */
    public function checkRegisterUnique(Request $request): JsonResponse
    {
        try {
            $register = $request->get('register');
            $kodeBarang = $request->get('kode_barang');
            $keadaanBarang = $request->get('keadaan_barang');
            $excludeId = $request->get('exclude_id'); // For edit form

            if (!$register) {
                return response()->json([
                    'success' => false,
                    'message' => 'Register harus diisi.'
                ]);
            }

            // PERBAIKAN: Untuk aset rusak berat, register boleh duplikat
            if ($keadaanBarang === 'RB') {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'is_unique' => true,
                        'exists' => false,
                        'message' => 'Register tersedia (aset rusak berat).'
                    ]
                ]);
            }

            // Untuk aset normal, cek uniqueness berdasarkan kombinasi kode_barang + register
            $query = Aset::where('register', $register);

            if ($kodeBarang) {
                $query->where('kode_barang', $kodeBarang);
            }

            // Exclude current record if editing
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            $exists = $query->exists();

            return response()->json([
                'success' => true,
                'data' => [
                    'is_unique' => !$exists,
                    'exists' => $exists,
                    'message' => $exists ? 'Kombinasi kode barang dan register sudah digunakan.' : 'Register tersedia.'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking register uniqueness: ' . $e->getMessage(), [
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat validasi register.'
            ], 500);
        }
    }

    /**
     * Extract hierarchy from aset for editing
     */
    private function extractHierarchy(Aset $aset): array
    {
        return $this->extractHierarchyFromSubSubRincianObjek($aset->subSubRincianObjek);
    }

    /**
     * Extract hierarchy from SubSubRincianObjek
     */
    private function extractHierarchyFromSubSubRincianObjek(SubSubRincianObjek $subSubRincianObjek): array
    {
        return [
            'akun' => $subSubRincianObjek->subRincianObjek->rincianObjek->objek->jenis->kelompok->akun,
            'kelompok' => $subSubRincianObjek->subRincianObjek->rincianObjek->objek->jenis->kelompok,
            'jenis' => $subSubRincianObjek->subRincianObjek->rincianObjek->objek->jenis,
            'objek' => $subSubRincianObjek->subRincianObjek->rincianObjek->objek,
            'rincianObjek' => $subSubRincianObjek->subRincianObjek->rincianObjek,
            'subRincianObjek' => $subSubRincianObjek->subRincianObjek,
            'subSubRincianObjek' => $subSubRincianObjek,
        ];
    }

    /**
     * Download PDF report for specific asset
     */
    public function downloadPdf(int $id)
    {
        try {
            // Get asset with all related data
            $aset = Aset::with([
                'subSubRincianObjek.subRincianObjek.rincianObjek.objek.jenis.kelompok.akun'
            ])->findOrFail($id);

            // Generate PDF with asset information and image
            $pdf = $this->generateAssetInfoPdf($aset);
            $pdfContent = $pdf->output();

            // If there's bukti_berita PDF, merge it
            if ($aset->bukti_berita && Storage::disk('public')->exists('bukti_berita/' . $aset->bukti_berita)) {
                $buktiBeritaPath = storage_path('app/public/bukti_berita/' . $aset->bukti_berita);
                $pdfContent = $this->mergePdfFiles($pdfContent, $buktiBeritaPath);
            }

            $fileName = 'aset_' . $aset->register . '_' . date('Y-m-d_H-i-s') . '.pdf';

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        } catch (\Exception $e) {
            Log::error('Error generating PDF for asset: ' . $e->getMessage(), [
                'asset_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunduh PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF with asset information and image
     */
    private function generateAssetInfoPdf(Aset $aset): \Barryvdh\DomPDF\PDF
    {
        // Check if bukti_barang image exists
        $imagePath = null;
        $imageBase64 = null;

        if ($aset->bukti_barang && Storage::disk('public')->exists('bukti_barang/' . $aset->bukti_barang)) {
            $imagePath = storage_path('app/public/bukti_barang/' . $aset->bukti_barang);

            // Convert image to base64 for PDF
            if (file_exists($imagePath)) {
                $imageData = file_get_contents($imagePath);
                $imageBase64 = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($imageData);
            }
        }

        // Extract hierarchy for display
        $hierarchy = $this->extractHierarchy($aset);

        $data = [
            'aset' => $aset,
            'hierarchy' => $hierarchy,
            'imageBase64' => $imageBase64,
            'generatedAt' => now()->format('d F Y H:i:s')
        ];

        // Generate PDF using view template
        $pdf = Pdf::loadView('asets.pdf', $data);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        return $pdf;
    }

    /**
     * Merge two PDF files using FPDI
     */
    private function mergePdfFiles(string $mainPdfContent, string $buktiBeritaPath): string
    {
        try {
            $fpdi = new Fpdi();

            // Add pages from main PDF (asset info)
            $tempMainFile = tempnam(sys_get_temp_dir(), 'main_pdf_');
            file_put_contents($tempMainFile, $mainPdfContent);

            $pageCount = $fpdi->setSourceFile($tempMainFile);
            for ($pageNum = 1; $pageNum <= $pageCount; $pageNum++) {
                $tpl = $fpdi->importPage($pageNum);
                $fpdi->AddPage();
                $fpdi->useTemplate($tpl);
            }

            // Add pages from bukti_berita PDF
            if (file_exists($buktiBeritaPath)) {
                $pageCount = $fpdi->setSourceFile($buktiBeritaPath);
                for ($pageNum = 1; $pageNum <= $pageCount; $pageNum++) {
                    $tpl = $fpdi->importPage($pageNum);
                    $fpdi->AddPage();
                    $fpdi->useTemplate($tpl);
                }
            }

            // Clean up temp file
            unlink($tempMainFile);

            return $fpdi->Output('', 'S'); // Return as string

        } catch (\Exception $e) {
            Log::warning('Failed to merge PDF files, returning main PDF only: ' . $e->getMessage());
            return $mainPdfContent; // Return main PDF if merge fails
        }
    }

    /**
     * Generate kode barang untuk aset rusak berat
     * Format khusus: 1.5.4.01.01.01.005
     */
    private function generateKodeBarangRusakBerat(): string
    {
        // Kode barang tetap untuk semua aset rusak berat
        return '1.5.4.01.01.01.005';
    }
}

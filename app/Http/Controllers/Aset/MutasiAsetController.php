<?php

namespace App\Http\Controllers\Aset;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mutasi\MutasiAsetRequest;
use App\Models\Aset;
use App\Models\MutasiAset;
use App\Repositories\MutasiAsetRepository;
use App\Services\MutasiAset\MutasiAsetService;
use App\Services\MutasiAset\MutasiAsetFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MutasiAsetController extends Controller
{
    public function __construct(
        private MutasiAsetRepository $repository,
        private MutasiAsetService $service,
        private MutasiAsetFileService $fileService
    ) {}

    /**
     * Display listing of mutations with search & filter
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'ruangan', 'tanggal_dari', 'tanggal_sampai']);
        $perPage = (int) $request->get('per_page', 10);

        $mutasi = $this->repository->getPaginatedWithFilters($filters, $perPage);

        return view('mutasi.index', [
            'mutasi' => $mutasi,
        ]);
    }

    /**
     * Show form to create new mutation
     */
    public function create()
    {
        $assets = $this->repository->getAvailableAssets();

        return view('mutasi.create', [
            'assets' => $assets,
        ]);
    }

    /**
     * Store new mutation
     */
    public function store(MutasiAsetRequest $request)
    {
        try {
            $mutasi = $this->service->createMutasi(
                $request->validated(),
                $request->file('berita_acara')
            );

            return redirect()->route('mutasi.show', $mutasi->id)
                ->with('success', 'Mutasi aset berhasil disimpan dan aset telah dipindahkan.');
        } catch (\InvalidArgumentException $e) {
            return back()
                ->with('error', $e->getMessage())
                ->withInput();
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
        $mutasi = $this->repository->findWithRelations($mutasi->id);

        return view('mutasi.show', [
            'mutasi' => $mutasi,
        ]);
    }

    /**
     * Display mutation history with filters
     */
    public function riwayat(Request $request)
    {
        $filters = $request->only(['search', 'ruangan', 'tanggal_dari', 'tanggal_sampai']);
        $perPage = (int) $request->get('per_page', 20);

        $riwayat = $this->repository->getPaginatedWithFilters($filters, $perPage);

        return view('mutasi.riwayat', [
            'riwayat' => $riwayat,
        ]);
    }

    /**
     * Display mutation history for specific asset
     */
    public function riwayatAset(Aset $aset)
    {
        $riwayat = $this->repository->getByAsset($aset);

        return view('mutasi.riwayat-aset', [
            'aset' => $aset,
            'riwayat' => $riwayat,
        ]);
    }

    /**
     * API: Search asset for mutation
     */
    public function cariAset(Request $request)
    {
        $search = $request->q ?? $request->search;
        $aset = $this->repository->searchAssets($search);

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
     * Download berita acara file
     */
    public function downloadBeritaAcara(MutasiAset $mutasi)
    {
        return $this->fileService->downloadBeritaAcara($mutasi);
    }

    /**
     * Preview berita acara in browser
     */
    public function previewBeritaAcara(MutasiAset $mutasi)
    {
        return $this->fileService->previewBeritaAcara($mutasi);
    }
}

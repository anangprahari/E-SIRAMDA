<?php

namespace App\Http\Controllers\Aset;

use App\Http\Controllers\Controller;
use App\Http\Requests\Aset\{StoreAsetRequest, UpdateAsetRequest};
use App\Models\{Akun, Kelompok, Jenis, Objek, RincianObjek, SubRincianObjek, SubSubRincianObjek, Aset};
use App\Services\Aset\{AsetService, AsetKodeService, AsetExportService, AsetPdfService,AsetLabelService};
use App\Repositories\AsetRepository;
use Illuminate\Http\Request;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AsetController extends Controller
{
    public function __construct(
        private AsetRepository $asetRepository,
        private AsetService $asetService,
        private AsetKodeService $kodeService,
        private AsetExportService $exportService,
        private AsetPdfService $pdfService,
        private AsetLabelService $labelService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $perPage = $this->getValidatedPerPage($request);

        $filters = [
            'search' => $request->input('search'),
            'tahun_perolehan' => $request->input('tahun_perolehan'),
            'tahun_dari' => $request->input('tahun_dari'),
            'tahun_sampai' => $request->input('tahun_sampai'),
            'keadaan_barang' => $request->input('keadaan_barang'),
            'ruangan' => $request->input('ruangan'),
        ];

        $asets = $this->asetRepository->getFilteredPaginated($filters, $perPage);

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAsetRequest $request): RedirectResponse
    {
        try {
            $result = $this->asetService->store(
                $request->validated(),
                $request->file('bukti_barang'),
                $request->file('bukti_berita')
            );

            $message = $this->asetService->generateStoreSuccessMessage($result);

            return redirect()->route('asets.index')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error creating aset: ' . $e->getMessage(), [
                'request' => $request->except(['bukti_barang', 'bukti_berita']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data aset: ' . $e->getMessage())
                ->withInput();
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
        $aset->load(['subSubRincianObjek.subRincianObjek.rincianObjek.objek.jenis.kelompok.akun']);

        $hierarchy = $this->extractHierarchy($aset);
        $dropdownData = $this->getDropdownDataForEdit($hierarchy);

        return view('asets.edit', array_merge(
            compact('aset', 'hierarchy'),
            $dropdownData
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAsetRequest $request, Aset $aset): RedirectResponse
    {
        Log::info('Update request received', [
            'aset_id' => $aset->id,
            'request_data' => $request->except(['bukti_barang', 'bukti_berita'])
        ]);

        try {
            $result = $this->asetService->update(
                $aset,
                $request->validated(),
                $request->file('bukti_barang'),
                $request->file('bukti_berita')
            );

            $message = $this->asetService->generateUpdateSuccessMessage($result);

            return redirect()->route('asets.index')->with('success', $message);
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
            $this->asetService->delete($aset);

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

    // ===================================
    // AJAX API METHODS - DROPDOWN
    // ===================================

    public function getKelompoks(int $akunId): JsonResponse
    {
        try {
            $kelompoks = Kelompok::where('akun_id', $akunId)
                ->orderByRaw('CAST(kode AS UNSIGNED) ASC')
                ->get(['id', 'nama', 'kode']);

            return response()->json(['success' => true, 'data' => $kelompoks]);
        } catch (\Exception $e) {
            Log::error('Error getting kelompoks: ' . $e->getMessage(), ['akun_id' => $akunId]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil data kelompok.'], 500);
        }
    }

    public function getJenis(int $kelompokId): JsonResponse
    {
        try {
            $jenis = Jenis::where('kelompok_id', $kelompokId)
                ->orderByRaw('CAST(kode AS UNSIGNED) ASC')
                ->get(['id', 'nama', 'kode']);

            return response()->json(['success' => true, 'data' => $jenis]);
        } catch (\Exception $e) {
            Log::error('Error getting jenis: ' . $e->getMessage(), ['kelompok_id' => $kelompokId]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil data jenis.'], 500);
        }
    }

    public function getObjeks(int $jenisId): JsonResponse
    {
        try {
            $objeks = Objek::where('jenis_id', $jenisId)
                ->orderByRaw('CAST(kode AS UNSIGNED) ASC')
                ->get(['id', 'nama', 'kode']);

            return response()->json(['success' => true, 'data' => $objeks]);
        } catch (\Exception $e) {
            Log::error('Error getting objeks: ' . $e->getMessage(), ['jenis_id' => $jenisId]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil data objek.'], 500);
        }
    }

    public function getRincianObjeks(int $objekId): JsonResponse
    {
        try {
            $rincianObjeks = RincianObjek::where('objek_id', $objekId)
                ->orderByRaw('CAST(kode AS UNSIGNED) ASC')
                ->get(['id', 'nama', 'kode']);

            return response()->json(['success' => true, 'data' => $rincianObjeks]);
        } catch (\Exception $e) {
            Log::error('Error getting rincian objeks: ' . $e->getMessage(), ['objek_id' => $objekId]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil data rincian objek.'], 500);
        }
    }

    public function getSubRincianObjeks(int $rincianObjekId): JsonResponse
    {
        try {
            $subRincianObjeks = SubRincianObjek::where('rincian_objek_id', $rincianObjekId)
                ->orderByRaw('CAST(kode AS UNSIGNED) ASC')
                ->get(['id', 'nama', 'kode']);

            return response()->json(['success' => true, 'data' => $subRincianObjeks]);
        } catch (\Exception $e) {
            Log::error('Error getting sub rincian objeks: ' . $e->getMessage(), ['rincian_objek_id' => $rincianObjekId]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil data sub rincian objek.'], 500);
        }
    }

    public function getSubSubRincianObjeks(int $subRincianObjekId): JsonResponse
    {
        try {
            $subSubRincianObjeks = SubSubRincianObjek::where('sub_rincian_objek_id', $subRincianObjekId)
                ->orderByRaw('CAST(kode AS UNSIGNED) ASC')
                ->get(['id', 'nama_barang', 'kode']);

            return response()->json(['success' => true, 'data' => $subSubRincianObjeks]);
        } catch (\Exception $e) {
            Log::error('Error getting sub sub rincian objeks: ' . $e->getMessage(), ['sub_rincian_objek_id' => $subRincianObjekId]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil data sub sub rincian objek.'], 500);
        }
    }

    // ===================================
    // AJAX API METHODS - KODE & REGISTER
    // ===================================

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

            $kodeBarang = $this->kodeService->generateKodeBarang($request->only([
                'akun_id',
                'kelompok_id',
                'jenis_id',
                'objek_id',
                'rincian_objek_id',
                'sub_rincian_objek_id',
                'sub_sub_rincian_objek_id'
            ]));

            return response()->json([
                'success' => true,
                'data' => ['kode_barang' => $kodeBarang]
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating kode barang: ' . $e->getMessage(), ['request_data' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat generate kode barang.'], 500);
        }
    }

    public function generateRegisterPreview(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'kode_barang' => 'required|string',
                'keadaan_barang' => 'nullable|string'
            ]);

            $data = $this->kodeService->generateRegisterPreview(
                $request->kode_barang,
                $request->keadaan_barang ?? 'B'
            );

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            Log::error('Error generating register preview: ' . $e->getMessage(), ['request_data' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat generate register preview.'], 500);
        }
    }

    public function getRegisterInfo(Request $request): JsonResponse
    {
        try {
            $request->validate(['kode_barang' => 'required|string']);

            $data = $this->kodeService->getRegisterInfo($request->kode_barang);

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            Log::error('Error getting register info: ' . $e->getMessage(), ['request_data' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil informasi register.'], 500);
        }
    }

    public function checkKodeBarangUnique(Request $request): JsonResponse
    {
        try {
            $exists = $this->asetRepository->existsByKodeBarangAndRegister(
                $request->get('kode_barang'),
                '',
                $request->get('exclude_id')
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'is_unique' => !$exists,
                    'exists' => $exists,
                    'message' => $exists ? 'Kode barang sudah digunakan.' : 'Kode barang tersedia.'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking kode barang uniqueness: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat validasi kode barang.'], 500);
        }
    }

    public function checkRegisterUnique(Request $request): JsonResponse
    {
        try {
            // Untuk rusak berat, register boleh duplikat
            if ($request->get('keadaan_barang') === 'RB') {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'is_unique' => true,
                        'exists' => false,
                        'message' => 'Register tersedia (aset rusak berat).'
                    ]
                ]);
            }

            $exists = $this->asetRepository->existsByKodeBarangAndRegister(
                $request->get('kode_barang'),
                $request->get('register'),
                $request->get('exclude_id')
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'is_unique' => !$exists,
                    'exists' => $exists,
                    'message' => $exists ? 'Kombinasi kode barang dan register sudah digunakan.' : 'Register tersedia.'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking register uniqueness: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat validasi register.'], 500);
        }
    }

    // ===================================
    // EXPORT & PDF
    // ===================================

    public function export(Request $request)
    {
        try {
            $filters = [
                'search' => $request->input('search'),
                'tahun_perolehan' => $request->input('tahun_perolehan'),
                'tahun_dari' => $request->input('tahun_dari'),
                'tahun_sampai' => $request->input('tahun_sampai'),
                'keadaan_barang' => $request->input('keadaan_barang'),
                'ruangan' => $request->input('ruangan'),
            ];

            return $this->exportService->export($filters);
        } catch (\Exception $e) {
            Log::error('Error exporting assets to Excel: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function downloadPdf(int $id)
    {
        try {
            $aset = $this->asetRepository->findWithRelations($id);

            if (!$aset) {
                return redirect()->back()->with('error', 'Aset tidak ditemukan.');
            }

            $pdfContent = $this->pdfService->generatePdf($aset);
            $fileName = $this->pdfService->generateFilename($aset);

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

    // ===================================
    // PRIVATE HELPER METHODS
    // ===================================

    private function getValidatedPerPage(Request $request): int
    {
        $perPage = $request->integer('per_page', 20);
        $allowedPerPage = [10, 20, 30, 50, 100];

        return in_array($perPage, $allowedPerPage) ? $perPage : 20;
    }

    private function extractHierarchy(Aset $aset): array
    {
        $subSubRincianObjek = $aset->subSubRincianObjek;

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

    private function getDropdownDataForEdit(array $hierarchy): array
    {
        return [
            'akuns' => Akun::orderByRaw('CAST(kode AS UNSIGNED) ASC')->get(),
            'kelompoks' => Kelompok::where('akun_id', $hierarchy['akun']->id)->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get(),
            'jenis' => Jenis::where('kelompok_id', $hierarchy['kelompok']->id)->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get(),
            'objeks' => Objek::where('jenis_id', $hierarchy['jenis']->id)->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get(),
            'rincianObjeks' => RincianObjek::where('objek_id', $hierarchy['objek']->id)->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get(),
            'subRincianObjeks' => SubRincianObjek::where('rincian_objek_id', $hierarchy['rincianObjek']->id)->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get(),
            'subSubRincianObjeks' => SubSubRincianObjek::where('sub_rincian_objek_id', $hierarchy['subRincianObjek']->id)->orderByRaw('CAST(kode AS UNSIGNED) ASC')->get(),
            'selectedValues' => [
                'akun_id' => $hierarchy['akun']->id,
                'kelompok_id' => $hierarchy['kelompok']->id,
                'jenis_id' => $hierarchy['jenis']->id,
                'objek_id' => $hierarchy['objek']->id,
                'rincian_objek_id' => $hierarchy['rincianObjek']->id,
                'sub_rincian_objek_id' => $hierarchy['subRincianObjek']->id,
                'sub_sub_rincian_objek_id' => $hierarchy['subSubRincianObjek']->id,
            ]
        ];
    }

    /**
     * Download asset label as image
     */
    public function downloadLabel(int $id)
    {
        try {
            // Fetch asset with necessary relations
            $aset = $this->asetRepository->findWithRelations($id);

            if (!$aset) {
                return redirect()->back()->with('error', 'Aset tidak ditemukan.');
            }

            // Generate label image via service
            $label = $this->labelService->generateLabelImage($aset);

            // Return as downloadable image
            return response($label['content'])
                ->header('Content-Type', $label['mime'])
                ->header('Content-Disposition', 'attachment; filename="' . $label['filename'] . '"');
        } catch (\Exception $e) {
            Log::error('Error generating label image: ' . $e->getMessage(), [
                'asset_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunduh label: ' . $e->getMessage());
        }
    }

    /**
     * Download batch labels as ZIP
     */
    public function downloadBatchLabel(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'asset_ids' => 'required|array|min:1',
                'asset_ids.*' => 'required|integer|exists:asets,id'
            ], [
                'asset_ids.required' => 'Pilih minimal satu aset untuk diunduh labelnya.',
                'asset_ids.min' => 'Pilih minimal satu aset untuk diunduh labelnya.',
                'asset_ids.*.exists' => 'Salah satu aset yang dipilih tidak valid.'
            ]);

            // Generate batch labels via service
            $zipPath = $this->labelService->generateBatchLabels($validated['asset_ids']);

            // Return ZIP as downloadable response with auto-cleanup
            return response()->download($zipPath, 'label-aset-terpilih.zip', [
                'Content-Type' => 'application/zip'
            ])->deleteFileAfterSend(true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->with('error', $e->validator->errors()->first());
        } catch (\Exception $e) {
            Log::error('Error generating batch label: ' . $e->getMessage(), [
                'asset_ids' => $request->input('asset_ids', []),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunduh label: ' . $e->getMessage());
        }
    }
}
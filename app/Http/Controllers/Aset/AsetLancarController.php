<?php

namespace App\Http\Controllers\Aset;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsetLancar\AsetLancarRequest;
use App\Models\AsetLancar;
use App\Models\RekeningUraian;
use App\Services\AsetLancar\AsetLancarService;
use App\Services\AsetLancar\AsetLancarExportService;
use Illuminate\Http\Request;

class AsetLancarController extends Controller
{
    public function __construct(
        protected AsetLancarService $service,
        protected AsetLancarExportService $exportService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 20);

        $asetLancars = $this->service->getFilteredList(
            $request->all(),
            $perPage
        );

        $rekeningUraians = RekeningUraian::orderBy('kode_rekening')->get();

        return view('asets.asetlancar.index', compact('asetLancars', 'rekeningUraians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rekeningUraians = RekeningUraian::orderBy('kode_rekening')->get();

        return view('asets.asetlancar.create', compact('rekeningUraians'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AsetLancarRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()
            ->route('aset-lancars.index')
            ->with('success', 'Data aset lancar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AsetLancar $asetLancar)
    {
        $asetLancar->load('rekeningUraian');

        return view('asets.asetlancar.show', compact('asetLancar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AsetLancar $asetLancar)
    {
        $rekeningUraians = RekeningUraian::orderBy('kode_rekening')->get();

        return view('asets.asetlancar.edit', compact('asetLancar', 'rekeningUraians'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AsetLancarRequest $request, AsetLancar $asetLancar)
    {
        $this->service->update($asetLancar, $request->validated());

        return redirect()
            ->route('aset-lancars.index')
            ->with('success', 'Data aset lancar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AsetLancar $asetLancar)
    {
        $this->service->delete($asetLancar);

        return redirect()
            ->route('aset-lancars.index')
            ->with('success', 'Data aset lancar berhasil dihapus.');
    }

    /**
     * Export to Excel.
     */
    public function export(Request $request)
    {
        $this->exportService->export($request->all());
    }

    /**
     * Get rekening uraian data for AJAX.
     */
    public function getRekeningUraian($id)
    {
        $rekening = RekeningUraian::find($id);

        if ($rekening) {
            return response()->json([
                'success' => true,
                'data' => $rekening
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }
}

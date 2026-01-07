<?php

namespace App\Services\Aset;

use App\Models\{Aset, SubSubRincianObjek};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Tcpdf\Fpdi;

class AsetPdfService
{
    public function __construct(
        private AsetFileService $fileService
    ) {}

    /**
     * Generate PDF untuk aset
     */
    public function generatePdf(Aset $aset): string
    {
        // Generate PDF with asset information and image
        $pdf = $this->generateAssetInfoPdf($aset);
        $pdfContent = $pdf->output();

        // If there's bukti_berita PDF, merge it
        if ($aset->bukti_berita && $this->fileService->buktiBeritaExists($aset->bukti_berita)) {
            $buktiBeritaPath = $this->fileService->getBuktiBeritaPath($aset->bukti_berita);
            $pdfContent = $this->mergePdfFiles($pdfContent, $buktiBeritaPath);
        }

        return $pdfContent;
    }

    /**
     * Generate PDF dengan informasi aset dan gambar
     */
    private function generateAssetInfoPdf(Aset $aset): \Barryvdh\DomPDF\PDF
    {
        // Check if bukti_barang image exists
        $imageBase64 = null;

        if ($aset->bukti_barang && $this->fileService->buktiBarangExists($aset->bukti_barang)) {
            $imagePath = $this->fileService->getBuktiBarangPath($aset->bukti_barang);

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
     * Merge two PDF files menggunakan FPDI
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
     * Extract hierarchy dari aset
     */
    private function extractHierarchy(Aset $aset): array
    {
        return $this->extractHierarchyFromSubSubRincianObjek($aset->subSubRincianObjek);
    }

    /**
     * Extract hierarchy dari SubSubRincianObjek
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
     * Generate filename untuk PDF
     */
    public function generateFilename(Aset $aset): string
    {
        return 'aset_' . $aset->register . '_' . date('Y-m-d_H-i-s') . '.pdf';
    }
}

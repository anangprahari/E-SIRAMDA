<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\AsetLancar;
use App\Models\MutasiAset;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * =====================================================
     * DASHBOARD UTAMA E-SIRAMDA
     * =====================================================
     *
     * Menyajikan:
     * - KPI 2 Pilar (Aset Tetap, Mutasi)
     * - Grafik kondisi aset tetap
     * - Mutasi terbaru
     * - Warning/alert
     */
    public function index(): View
    {
        // ========================================
        // 1. KPI CARDS - 2 PILAR UTAMA
        // ========================================

        // Total Aset Tetap (dalam unit)
        $totalAsetTetap = Aset::count();

        // Total Mutasi Aset
        $totalMutasi = MutasiAset::count();

        // ========================================
        // 2. GRAFIK KONDISI ASET TETAP
        // ========================================

        // Distribusi kondisi aset: Baik, Kurang Baik, Rusak Berat
        $kondisiAset = Aset::select('keadaan_barang', DB::raw('count(*) as total'))
            ->groupBy('keadaan_barang')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->keadaan_barang => $item->total];
            });

        // Pastikan semua kondisi ada (default 0 jika tidak ada data)
        $kondisiAsetData = [
            'B' => $kondisiAset->get('B', 0),
            'KB' => $kondisiAset->get('KB', 0),
            'RB' => $kondisiAset->get('RB', 0),
        ];

        // ========================================
        // 3. MUTASI TERBARU (5 DATA)
        // ========================================

        $mutasiTerbaru = MutasiAset::with(['aset', 'user'])
            ->latest('tanggal_mutasi')
            ->take(5)
            ->get();

        // ========================================
        // 4. WARNING / ALERT - ASET TETAP ONLY
        // ========================================

        // Aset Tetap Rusak Berat
        $asetRusakBerat = Aset::where('keadaan_barang', 'RB')->count();

        // Aset Tetap tanpa lokasi
        $asetTanpaLokasi = Aset::whereNull('lokasi_barang')
            ->orWhere('lokasi_barang', '')
            ->count();

        // Aset Tetap tanpa ruangan
        $asetTanpaRuangan = Aset::where(function ($q) {
            $q->whereNull('ruangan')
                ->orWhere('ruangan', '');
        })
            ->count();

        // Aset Tetap belum pernah dimutasi (yang punya ruangan)
        $asetBelumDimutasi = Aset::whereDoesntHave('mutasi')->count();

        // ========================================
        // 5. STATISTIK TAMBAHAN
        // ========================================

        // Total nilai aset tetap
        $totalNilaiAsetTetap = Aset::sum(DB::raw('jumlah_barang * harga_satuan'));

        // Mutasi bulan ini
        $mutasiBulanIni = MutasiAset::whereMonth('tanggal_mutasi', now()->month)
            ->whereYear('tanggal_mutasi', now()->year)
            ->count();

        // Aset per kondisi untuk persentase
        $persentaseKondisi = $this->hitungPersentaseKondisi($kondisiAsetData, $totalAsetTetap);

        // ========================================
        // 6. ASET KOPTABLE (SECONDARY KPI)
        // ========================================

        // Aset Koptable: aset dengan harga_satuan < 1.250.000
        $batasKoptable = 1250000;

        // Total jumlah aset koptable (dalam unit)
        $totalAsetKoptable = Aset::where('harga_satuan', '<', $batasKoptable)
            ->count();

        // Total nilai aset koptable (sum of harga)
        $totalNilaiAsetKoptable = Aset::where('harga_satuan', '<', $batasKoptable)
            ->sum(DB::raw('jumlah_barang * harga_satuan'));

        // ========================================
        // RETURN VIEW DENGAN DATA
        // ========================================

        return view('dashboard', compact(
            // KPI
            'totalAsetTetap',
            'totalMutasi',

            // Grafik
            'kondisiAsetData',

            // Mutasi
            'mutasiTerbaru',

            // Warning Aset Tetap
            'asetRusakBerat',
            'asetTanpaLokasi',
            'asetTanpaRuangan',
            'asetBelumDimutasi',

            // Aset Koptable (Secondary KPI)
            'totalAsetKoptable',
            'totalNilaiAsetKoptable',
            'batasKoptable',

            // Tambahan
            'totalNilaiAsetTetap',
            'mutasiBulanIni',
            'persentaseKondisi'
        ));
    }

    /**
     * Hitung persentase kondisi aset
     */
    private function hitungPersentaseKondisi(array $kondisiData, int $total): array
    {
        if ($total === 0) {
            return [
                'B' => 0,
                'KB' => 0,
                'RB' => 0,
            ];
        }

        return [
            'B' => round(($kondisiData['B'] / $total) * 100, 1),
            'KB' => round(($kondisiData['KB'] / $total) * 100, 1),
            'RB' => round(($kondisiData['RB'] / $total) * 100, 1),
        ];
    }

    /**
     * =====================================================
     * METODE HELPER UNTUK DASHBOARD
     * =====================================================
     */

    /**
     * Get top 5 aset berdasarkan nilai tertinggi
     */
    public function getTopAsetByValue()
    {
        return Aset::selectRaw('*, (jumlah_barang * harga_satuan) as total_nilai')
            ->orderByDesc('total_nilai')
            ->take(5)
            ->get();
    }

    /**
     * Get distribusi aset per tahun perolehan (5 tahun terakhir)
     */
    public function getAsetByTahunPerolehan()
    {
        $tahunSekarang = date('Y');
        $tahunMulai = $tahunSekarang - 4;

        return Aset::select('tahun_perolehan', DB::raw('count(*) as total'))
            ->whereBetween('tahun_perolehan', [$tahunMulai, $tahunSekarang])
            ->groupBy('tahun_perolehan')
            ->orderBy('tahun_perolehan')
            ->get();
    }

    /**
     * Get ruangan dengan aset terbanyak (top 5)
     */
    public function getTopRuanganByAset()
    {
        return Aset::select('ruangan', DB::raw('count(*) as total'))
            ->whereNotNull('ruangan')
            ->where('ruangan', '!=', '')
            ->groupBy('ruangan')
            ->orderByDesc('total')
            ->take(5)
            ->get();
    }

    /**
     * Get timeline mutasi (per bulan, 6 bulan terakhir)
     */
    public function getMutasiTimeline()
    {
        return MutasiAset::selectRaw('
            DATE_FORMAT(tanggal_mutasi, "%Y-%m") as bulan,
            COUNT(*) as total
        ')
            ->where('tanggal_mutasi', '>=', now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
    }
}

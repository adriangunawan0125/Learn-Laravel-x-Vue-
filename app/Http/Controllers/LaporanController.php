<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'jenis' => 'nullable|in:masuk,keluar',
        ]);

        $query = RiwayatStok::with('produk:id,nama,kategori,harga,stok')
            ->when(
                $filters['tanggal_mulai'] ?? null,
                fn ($q, $tanggal) => $q->whereDate('tanggal', '>=', $tanggal)
            )
            ->when(
                $filters['tanggal_akhir'] ?? null,
                fn ($q, $tanggal) => $q->whereDate('tanggal', '<=', $tanggal)
            )
            ->when(
                $filters['jenis'] ?? null,
                fn ($q, $jenis) => $q->where('jenis', $jenis)
            );

        $transaksi = (clone $query)
            ->latest('tanggal')
            ->latest('id')
            ->get();

        $totalMasuk = (clone $query)
            ->where('jenis', 'masuk')
            ->sum('jumlah');

        $totalKeluar = (clone $query)
            ->where('jenis', 'keluar')
            ->sum('jumlah');

        $nilaiPersediaan = Produk::selectRaw(
            'COALESCE(SUM(harga * stok), 0) as total'
        )->value('total');

        return Inertia::render('Laporan/Index', [
            'transaksi' => $transaksi,
            'filters' => [
                'tanggal_mulai' => $filters['tanggal_mulai'] ?? '',
                'tanggal_akhir' => $filters['tanggal_akhir'] ?? '',
                'jenis' => $filters['jenis'] ?? '',
            ],
            'ringkasan' => [
                'totalProduk' => Produk::count(),
                'totalStok' => (int) Produk::sum('stok'),
                'totalMasuk' => (int) $totalMasuk,
                'totalKeluar' => (int) $totalKeluar,
                'jumlahTransaksi' => $transaksi->count(),
                'nilaiPersediaan' => (float) $nilaiPersediaan,
            ],
        ]);
    }

    public function exportCsv(Request $request)
    {
        $filters = $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'jenis' => 'nullable|in:masuk,keluar',
        ]);

        $transaksi = RiwayatStok::with('produk:id,nama,kategori')
            ->when(
                $filters['tanggal_mulai'] ?? null,
                fn ($q, $tanggal) => $q->whereDate('tanggal', '>=', $tanggal)
            )
            ->when(
                $filters['tanggal_akhir'] ?? null,
                fn ($q, $tanggal) => $q->whereDate('tanggal', '<=', $tanggal)
            )
            ->when(
                $filters['jenis'] ?? null,
                fn ($q, $jenis) => $q->where('jenis', $jenis)
            )
            ->latest('tanggal')
            ->latest('id')
            ->get();

        $filename = 'laporan-inventaris-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($transaksi) {
            $output = fopen('php://output', 'w');

            // BOM agar karakter Indonesia terbaca baik di Excel.
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'No',
                'Tanggal',
                'Nama Produk',
                'Kategori',
                'Jenis Transaksi',
                'Jumlah',
                'Keterangan',
            ], ';');

            foreach ($transaksi as $index => $item) {
                fputcsv($output, [
                    $index + 1,
                    $item->tanggal,
                    $item->produk?->nama ?? 'Produk dihapus',
                    $item->produk?->kategori ?? '-',
                    strtoupper($item->jenis),
                    $item->jumlah,
                    $item->keterangan ?? '-',
                ], ';');
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    
public function exportPdf(Request $request)
{
    $filters = $request->validate([
        'tanggal_mulai' => 'nullable|date',
        'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
        'jenis' => 'nullable|in:masuk,keluar',
    ]);

    $query = RiwayatStok::with('produk:id,nama,kategori')
        ->when(
            $filters['tanggal_mulai'] ?? null,
            fn ($q, $tanggal) => $q->whereDate('tanggal', '>=', $tanggal)
        )
        ->when(
            $filters['tanggal_akhir'] ?? null,
            fn ($q, $tanggal) => $q->whereDate('tanggal', '<=', $tanggal)
        )
        ->when(
            $filters['jenis'] ?? null,
            fn ($q, $jenis) => $q->where('jenis', $jenis)
        );

    $transaksi = $query
        ->latest('tanggal')
        ->latest('id')
        ->get();

    $totalMasuk = (clone $query)
        ->where('jenis', 'masuk')
        ->sum('jumlah');

    $totalKeluar = (clone $query)
        ->where('jenis', 'keluar')
        ->sum('jumlah');

    $nilaiPersediaan = Produk::selectRaw(
        'COALESCE(SUM(harga * stok), 0) as total'
    )->value('total');

    $ringkasan = [
        'totalProduk' => Produk::count(),
        'totalStok' => (int) Produk::sum('stok'),
        'totalMasuk' => (int) $totalMasuk,
        'totalKeluar' => (int) $totalKeluar,
        'nilaiPersediaan' => (float) $nilaiPersediaan,
    ];

    $pdf = Pdf::loadView('laporan.pdf', [
        'transaksi' => $transaksi,
        'ringkasan' => $ringkasan,
        'tanggalMulai' => $filters['tanggal_mulai'] ?? null,
        'tanggalAkhir' => $filters['tanggal_akhir'] ?? null,
        'tanggalCetak' => now()->format('d/m/Y H:i'),
    ])->setPaper('a4', 'landscape');

    return $pdf->download('laporan-inventaris-' . now()->format('Y-m-d') . '.pdf');
}

}

<?php

namespace App\Http\Controllers;
use App\Models\RiwayatStok;
use Carbon\Carbon;
use App\Models\Produk;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProdukController extends Controller
{
  
public function dashboard()
{
    $totalProduk = Produk::count();
    $totalStok = Produk::sum('stok');

    $stokHabis = Produk::where('stok', 0)->count();

    $stokMenipis = Produk::where('stok', '>', 0)
        ->whereColumn('stok', '<=', 'stok_minimum')
        ->count();

    $produkTerbaru = Produk::latest()
        ->take(5)
        ->get();

    // Siapkan data transaksi untuk 6 bulan terakhir.
    $chartTransaksi = collect(range(5, 0))->map(function ($offset) {
        $bulan = Carbon::now()
            ->startOfMonth()
            ->subMonths($offset);

        $awalBulan = $bulan->copy()->startOfMonth();
        $akhirBulan = $bulan->copy()->endOfMonth();

        return [
            'bulan' => $bulan->translatedFormat('M'),
            'masuk' => (int) RiwayatStok::where('jenis', 'masuk')
                ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
                ->sum('jumlah'),
            'keluar' => (int) RiwayatStok::where('jenis', 'keluar')
                ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
                ->sum('jumlah'),
        ];
    })->values();

    return Inertia::render('Dashboard', [
        'stats' => [
            'totalProduk' => $totalProduk,
            'totalStok' => $totalStok,
            'stokMenipis' => $stokMenipis,
            'stokHabis' => $stokHabis,
        ],
        'produkTerbaru' => $produkTerbaru,
        'chartTransaksi' => $chartTransaksi,
    ]);
}


    public function index()
    {
        $produks = Produk::latest()->get();

        return Inertia::render('Produk/Index', [
            'produks' => $produks,
        ]);
    }

    public function create()
    {
        return Inertia::render('Produk/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0|max:1000000',
        ]);

        Produk::create($validated);

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        return Inertia::render('Produk/Edit', [
            'produk' => $produk,
        ]);
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0|max:1000000',
        ]);

        $produk->update($validated);

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}

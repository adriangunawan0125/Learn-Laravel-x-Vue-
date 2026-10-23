<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class RiwayatStokController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'nullable|in:masuk,keluar',
            'search' => 'nullable|string|max:100',
        ]);

        $riwayat = RiwayatStok::with('produk:id,nama,kategori')
            ->when(
                $validated['jenis'] ?? null,
                fn ($query, $jenis) => $query->where('jenis', $jenis)
            )
            ->when(
                $validated['search'] ?? null,
                function ($query, $search) {
                    $query->whereHas('produk', function ($produkQuery) use ($search) {
                        $produkQuery->where('nama', 'like', "%{$search}%");
                    });
                }
            )
            ->latest('tanggal')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('RiwayatStok/Index', [
            'riwayat' => $riwayat,
            'filters' => [
                'jenis' => $validated['jenis'] ?? '',
                'search' => $validated['search'] ?? '',
            ],
            'ringkasan' => [
                'totalMasuk' => RiwayatStok::where('jenis', 'masuk')->sum('jumlah'),
                'totalKeluar' => RiwayatStok::where('jenis', 'keluar')->sum('jumlah'),
                'jumlahTransaksi' => RiwayatStok::count(),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('RiwayatStok/Create', [
            'produks' => Produk::orderBy('nama')
                ->get(['id', 'nama', 'kategori', 'stok']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|integer|exists:produks,id',
            'jenis' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1|max:1000000',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($validated) {
            $produk = Produk::whereKey($validated['produk_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $validated['jenis'] === 'keluar'
                && $produk->stok < $validated['jumlah']
            ) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Stok tidak mencukupi. Stok tersedia: '
                        . $produk->stok . ' unit.',
                ]);
            }

            if ($validated['jenis'] === 'masuk') {
                $produk->increment('stok', $validated['jumlah']);
            } else {
                $produk->decrement('stok', $validated['jumlah']);
            }

            RiwayatStok::create($validated);
        });

        return redirect()
            ->route('riwayat-stok.index')
            ->with('success', 'Transaksi stok berhasil disimpan.');
    }
}

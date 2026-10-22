<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::latest()->get();

        return Inertia::render('Produk/Index', [
            'produks' => $produks,
        ]);
    }


public function create()
{
    return \Inertia\Inertia::render('Produk/Create');
}

public function store(\Illuminate\Http\Request $request)
{
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'kategori' => 'required|string|max:255',
        'harga' => 'required|numeric|min:0',
        'stok' => 'required|integer|min:0',
    ]);

    \App\Models\Produk::create($validated);

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
    ]);

    $produk->update($validated);

    return redirect()->route('produk.index');
}
}
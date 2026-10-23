<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'nama',
        'kategori',
        'harga',
        'stok',
        'stok_minimum',
    ];

    public function riwayatStok(): HasMany
{
    return $this->hasMany(RiwayatStok::class);
}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $primaryKey = 'produk_id';

    protected $fillable = [
        'nama_produk',
        'keterangan_produk',
        'harga',
    ];

    // Relasi dengan tabel pesanan detail (many to many)
    public function pesananDetails()
    {
        return $this->hasMany(PesananDetail::class, 'produk_id', 'produk_id');
    }
}

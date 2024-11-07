<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananDetail extends Model
{
    use HasFactory;

    protected $table = 'pesanan_detail';

    protected $primaryKey = 'pesanan_detail_id';

    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'jumlah',
    ];

    // Relasi dengan pesanan (many to one)
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'pesanan_id');
    }

    // Relasi dengan produk (many to one)
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }
}

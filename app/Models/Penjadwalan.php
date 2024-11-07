<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjadwalan extends Model
{
    use HasFactory;

    protected $table = 'penjadwalan';

    protected $primaryKey = 'penjadwalan_id';

    protected $fillable = [
        'pesanan_id',
        'urutan_prioritas',
        'estimasi_selesai',
    ];

    // Relasi dengan pesanan (many to one)
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'pesanan_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $primaryKey = 'pesanan_id';

    protected $fillable = [
        'nama_pemesan',
        'kode_pesanan',
        'status',
        'dibuat_oleh',
        'channel',
        'tanggal_pesanan',
        'tanggal_pengiriman',
    ];

    // Relasi dengan pengguna (many to one)
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh', 'pengguna_id');
    }

    // Relasi dengan pesanan detail (one to many)
    public function pesananDetails()
    {
        return $this->hasMany(PesananDetail::class, 'pesanan_id', 'pesanan_id');
    }
}

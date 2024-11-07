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

    // Relasi dengan penjadwalan (one to one)
    public function penjadwalan()
    {
        return $this->hasOne(Penjadwalan::class, 'pesanan_id', 'pesanan_id');
    }
}

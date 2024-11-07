<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'pengguna';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role'
    ];

    protected $primaryKey = 'pengguna_id';

    protected $hidden = [
        'password'
    ];

    // Relasi ke Pesanan
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }

    // Relasi ke Penjadwalan (sebagai manajer)
    public function penjadwalan()
    {
        return $this->hasMany(Penjadwalan::class, 'manajer_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory;

    protected $table = 'pengguna';

    protected $primaryKey = 'pengguna_id';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'status_akun',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi dengan tabel pesanan (one to many)
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'dibuat_oleh', 'pengguna_id');
    }
}

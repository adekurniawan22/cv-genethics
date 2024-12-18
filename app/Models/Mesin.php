<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesin extends Model
{
    use HasFactory;

    protected $table = 'mesin';

    protected $primaryKey = 'mesin_id';

    protected $fillable = [
        'nama_mesin',
        'status',
        'keterangan_mesin',
        'kapasitas_per_hari',
    ];
}

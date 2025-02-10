<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    protected $table = 'hari_libur';
    protected $primaryKey = 'hari_libur_id';
    public $timestamps = false;

    protected $dates = ['tanggal'];

    protected $fillable = [
        'tanggal',
        'keterangan',
    ];
}

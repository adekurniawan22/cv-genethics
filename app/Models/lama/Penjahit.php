<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjahit extends Model
{
    protected $table = 'penjahit';
    protected $primaryKey = 'penjahit_id';

    protected $fillable = [
        'nama',
        'kontak',
    ];
}

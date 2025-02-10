<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjadwalan extends Model
{
    protected $table = 'penjadwalan';
    protected $fillable = [
        'pesanan_id',
        'due_date',
        'waktu_mulai',
        'waktu_selesai',
        'completion_time',
        'lateness',
        'mesin'
    ];

    protected $casts = [
        'mesin' => 'json'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}

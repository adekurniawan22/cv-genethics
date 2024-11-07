<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Penjadwalan extends Model
{
    protected $table = 'penjadwalan';

    protected $fillable = [
        'manajer_id',
        'detail_penjadwalan',
        'tanggal'
    ];

    protected $primaryKey = 'penjadwalan_id';

    // Relasi ke Pengguna (manajer)
    public function manajer()
    {
        return $this->belongsTo(Pengguna::class, 'manajer_id');
    }

    // Fungsi untuk menghitung jumlah penjadwalan bulan ini
    public static function jumlahBulanIni()
    {
        $now = Carbon::now('Asia/Jakarta');
        $startOfMonth = $now->copy()->startOfMonth()->toDateTimeString();
        $endOfMonth = $now->copy()->endOfMonth()->toDateTimeString();

        return self::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->count();
    }
}

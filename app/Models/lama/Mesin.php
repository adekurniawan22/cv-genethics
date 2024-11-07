<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesin extends Model
{
    protected $table = 'mesin';

    protected $primaryKey = 'mesin_id';

    protected $fillable = [
        'nama_mesin',
        'status',
    ];
}

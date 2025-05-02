<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $table = "years";
    public $timestamps = false;
    protected $fillable = [
        'year',
        'akun',
        'keg',
        'sub_keg',
        'kd_id_barang'
    ];

    protected $casts = [
        'year' => 'integer',
        'akun' => 'integer',
        'keg' => 'integer',
        'sub_keg' => 'integer',
        'kd_id_barang' => 'integer'
    ];
}

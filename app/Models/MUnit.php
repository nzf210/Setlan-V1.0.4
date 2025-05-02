<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MUnit extends Model
{
    use HasFactory;

    protected $table = 'm_units';
    protected $primaryKey = 'id_unit';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [

        'id_unit',
        'id_opd',
        'nama_unit',
    ];
}

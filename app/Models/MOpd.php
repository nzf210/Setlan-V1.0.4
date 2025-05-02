<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MOpd extends Model
{
    use HasFactory;
    protected $table = 'm_opds';
    protected $primaryKey = 'id_opd';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_opd',
        'id_kab',
        'nama_opd'
    ];
}

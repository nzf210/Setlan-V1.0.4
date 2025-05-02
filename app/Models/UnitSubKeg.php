<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitSubKeg extends Model
{
    use HasFactory;
    protected $table = 'unit_sub_kegiatan';
    protected $fillable = [
        'id',
        'id_kab',
        'id_opd',
        'id_unit',
        'id_subkeg',
        'tahun',
    ];
    public $timestamps = false;
    public function kabupaten()
    {
        return $this->belongsTo(MKab::class, 'id_kab','id_kab');
    }

    public function opd()
    {
        return $this->belongsTo(MOpd::class, 'id_opd','id_opd');
    }

    public function unit()
    {
        return $this->belongsTo(MUnit::class, 'id_unit','id_unit');
    }

    public function subKegiatan()
    {
        return $this->belongsTo(MSubkeg::class, 'id_subkeg','id_subkeg');
    }
}

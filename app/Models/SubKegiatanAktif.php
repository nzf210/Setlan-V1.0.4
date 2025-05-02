<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKegiatanAktif extends Model
{
    use HasFactory;
    protected $table = 'sub_kegiatan_aktif';
    protected $fillable = [
        'id_sub_kegiatan_aktif',
        'id_kabuten',
        'id_opd',
        'id_unit',
        'id_sub_kegiatan',
        'tahun',
    ];
    public $timestamps = false;
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'id_kabupaten','id_kabupaten');
    }

    public function opd()
    {
        return $this->belongsTo(Opd::class, 'id_opd','id_opd');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'id_unit','id_unit');
    }

    public function sub_kegiatan()
    {
        return $this->belongsTo(SubKegiatan::class, 'id_subkegiatan','id_subkegiatan');
    }
}

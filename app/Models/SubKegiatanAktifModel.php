<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKegiatanAktifModel extends Model
{
    use HasFactory;
    protected $table = 'sub_kegiatan_aktif';
    protected $primaryKey = 'id_sub_kegiatan_aktif';
    protected $fillable = [
        'id_sub_kegiatan_aktif',
        'id_sub_kegiatan',
        'id_kabupaten',
        'id_opd',
        'id_unit',
        'tahun',
    ];
    public $timestamps = false;
    public function kabupaten()
    {
        return $this->belongsTo(KabupatenModel::class, 'id_kabupaten','id_kabupaten');
    }

    public function opd()
    {
        return $this->belongsTo(OpdModel::class, 'id_opd','id_opd');
    }

    public function unit()
    {
        return $this->belongsTo(UnitModel::class, 'id_unit','id_unit');
    }

    public function sub_kegiatan()
    {
        return $this->belongsTo(SubKegiatanModel::class, 'id_sub_kegiatan','id_sub_kegiatan');
    }
}

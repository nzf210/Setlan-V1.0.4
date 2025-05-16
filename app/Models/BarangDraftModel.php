<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class BarangDraftModel extends Model
{
    use HasFactory;

    protected $table = 'draft_barang_masuk';
    protected $primaryKey = 'id_draft';
    protected $fillable = [
        'id_barang',
        'id_unit',
        'id_opd',
        'id_kabupaten',
        'id_kegiatan',
        'id_subkegiatan',
        'type',
        'tgl_beli',
        'tahun_buat',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function unit()
    {
        return $this->belongsTo(UnitModel::class, 'id_unit', 'id_unit');
    }
    public function opd()
    {
        return $this->belongsTo(OpdModel::class, 'id_opd', 'id_opd');
    }
    public function kabupaten()
    {
        return $this->belongsTo(KabupatenModel::class, 'id_kabupeten', 'id_kabupaten');
    }
    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'id_barang', 'id_barang');
    }
    public function sub_kegiatan_aktif()
    {
        return $this->belongsTo(SubKegiatanAktifModel::class, 'id_subkegiatan_aktif', 'id_subkegiatan_aktif');
    }
    public function  scopeFiltered(Builder $quary)
    {
        if (request('nama_akun') == null) {
            return $quary;
        }
        $quary
            ->when(request('nama_akun'), function (Builder $q) {
                $q->where('nama', 'like', '%' . request('nama_akun') . '%');
            })
            ->when(request('sort_by'), function (Builder $q) {
                $sortBy = request('sort_by');
                $sortOrder = request('sort_order', 'asc');
                if (in_array($sortBy, ['nama'])) {
                    $q->orderBy($sortBy, $sortOrder);
                }
            });
    }
}

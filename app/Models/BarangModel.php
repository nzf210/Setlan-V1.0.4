<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangModel extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_barang';
    protected $keyType = 'string';
    protected $fillable = [
        'id_barang',
        'id_kode_barang',
        'nama_barang',
        'merek',
        'type',
        'tahun_buat',
        'tahun_beli',
        'id_akun',
        'id_satuan',
        'harga',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function kode_barang()
    {
        return $this->belongsTo(KodeBarangModel::class, 'id_kode_barang', 'id_kode_barang');
    }
    public function satuan()
    {
        return $this->belongsTo(SatuanModel::class, 'id_satuan', 'id_satuan');
    }

    public function akun(){
        return $this->belongsTo(AkunAktifModel::class,'id_akun','id_akun');
    }

    public function  scopeFiltered(Builder $quary)
    {
        $quary
            ->when(request('kode_nama_barang'), function (Builder $q) {
                $q->whereIn('id_kd_barang', request('kode_nama_barang'));
            })
            ->when(request('satuan'), function (Builder $q) {
                $q->whereIn('satuan_id', request('satuan'));
            })
            ->when(request('search'), function (Builder $q) {
                $q->where('nama_barang', 'like', '%' . request('search') . '%');
            })
            ->when(request('kd_barang_search'), function (Builder $q) {
                $q->whereHas('category', function (Builder $subQuery) {
                    $subQuery->where('nama', 'like', '%' . request('kd_barang_search') . '%');
                });
            })
            ->when(request('harga'), function (Builder $q) {
                $q->whereBetween('harga', [
                    request('harga.from', 0),
                    request('harga.to', 100000000000),
                ]);
            })
            ->when(request('sort_by'), function (Builder $q) {
                $sortBy = request('sort_by');
                $sortOrder = request('sort_order', 'asc');
                if (in_array($sortBy, ['nama_barang', 'harga', 'created_at'])) {
                    $q->orderBy($sortBy, $sortOrder);
                }
            });
    }
}

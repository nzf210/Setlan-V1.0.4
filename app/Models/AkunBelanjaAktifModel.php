<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunBelanjaAktifModel extends Model
{
    use HasFactory;
    protected $table = 'akun_aktif';
    protected $primaryKey = 'id_akun_aktif';
    public $timestamps = false;
    protected $fillable = [
        'id_akun_aktif',
        'id_akun',
        'id_kabupaten',
        'tahun'
    ];


    public function kabupaten()
    {
        return $this->belongsTo(KabupatenModel::class, 'id_kabupaten','id_kabupaten');
    }

    public function akun(){
        return $this->belongsTo(AkunBelanjaModel::class, 'id_akun','id_akun');
    }

    public function scopeFiltered(Builder $query)
{
    $query
        ->when(request('nama'), function (Builder $q) {
            $q->whereHas('akun', function ($subQuery) {
                $subQuery->where('nama_akun', 'like', '%' . request('nama') . '%');
            });
        })
        ->when(request('sort_by'), function (Builder $q) {
            $sortBy = request('sort_by');
            $sortOrder = request('sort_order', 'asc');

            if ($sortBy === 'nama_akun') {
                $q->join('akun_belanja', 'akun_aktif.id_akun', '=', 'akun_belanja.id_akun')
                    ->orderBy('akun_belanja.nama_akun', $sortOrder);
            }

            elseif (in_array($sortBy, ['nama_akun_aktif'])) {
                $q->orderBy($sortBy, $sortOrder);
            }
        });
}

}

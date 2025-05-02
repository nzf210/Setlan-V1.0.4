<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunAktifModel extends Model
{
    use HasFactory;
    protected $table = 'akunaktif';
    public $timestamps = false;
    protected $fillable = [
        'ids',
        'id_akun',
        'id_kab',
        'nama',
        'tahun'
    ];


    public function kabupaten()
    {
        return $this->belongsTo(KabupatenModel::class, 'id_kabupaten','id_kabupaten');
    }

    public function akun(){
        return $this->belongsTo(AkunBelanjaModel::class, 'ids','id');
    }

    public function  scopeFiltered(Builder $quary)
    {
        if (request('nama') == null) {
            return $quary;
        }
        $quary
            ->when(request('nama'), function (Builder $q) {
                $q->where('nama', 'like', '%' . request('nama') . '%');
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

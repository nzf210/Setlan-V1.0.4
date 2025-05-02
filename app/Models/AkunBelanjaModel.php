<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunBelanjaModel extends Model
{
    use HasFactory;
    protected $table = 'akun_belanja';
    protected $primaryKey = 'id_akun_belanja';
    public $timestamps = false;

}

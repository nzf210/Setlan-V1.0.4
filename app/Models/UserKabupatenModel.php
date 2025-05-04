<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserKabupatenModel extends Model
{
    use HasFactory;
    protected $table = "user_kabupaten";

    protected $fillable = [
        'id_kabupaten',
        'kode_kabupaten',
        'id_user'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users' );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUnit extends Model
{
    use HasFactory;

    protected $table = "user_unit";

    protected $fillable = [
        'id_unit',
        'id_user'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

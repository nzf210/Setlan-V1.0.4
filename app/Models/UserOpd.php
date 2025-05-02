<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOpd extends Model
{
    use HasFactory;

    protected $table = "user_opd";

    protected $fillable = [
        'id_opd',
        'id_user'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

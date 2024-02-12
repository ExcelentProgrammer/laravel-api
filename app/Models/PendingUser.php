<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingUser extends Model
{
    use HasFactory, BaseModel;

    public $fillable = [
        "name",
        "email",
        "password"
    ];

    protected $hidden = [
        "password"
    ];
    protected $casts = [
        "password" => "hashed"
    ];
}

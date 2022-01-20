<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'password'];
    const CREATED_AT = 'created_at';
    const UPDATE_AT = 'updated_at';
    protected $hidden = ['password'];
    
}

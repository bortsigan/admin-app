<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    public $timestamps = false; 

    const ROLE_ADMIN = 1;
    const ROLE_CLIENT = 2;
}

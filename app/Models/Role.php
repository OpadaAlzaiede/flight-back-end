<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected const ROLES = [
        'PASSENGER' => 1,
        'DRIVER' => 2
    ];

    public function getRolesArray() {

        return self::ROLES;
    }
}

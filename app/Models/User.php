<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Trip;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'father_name',
        'email',
        'password',
        'phone',
        'id_photo',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role() {

        return $this->belongsTo(Role::class);
    }

    public function trips() {

        return $this->belongsToMany(Trip::class);
    }

    public function isPassenger() {

        return $this->role_id === Role::getRolesArray()['PASSENGER'];
    }

    public function isDriver() {

        return $this->role_id === Role::getRolesArray()['DRIVER'];
    }
}

<?php

namespace App\Models;

use App\Models\User;
use App\Models\Governorate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'starts_at',
        'governorate_id',
        'details',
        'number_of_seats',
        'estimated_time',
        'car_plate',
        'cost'
    ];

    public function users() {

        return $this->belongsToMany(User::class);
    }

    public function governorate() {

        return $this->belongsTo(Governorate::class);
    }

    public function driver() {

        return $this->belongsTo(User::class, 'user_id');
    }
}

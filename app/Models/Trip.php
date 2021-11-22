<?php

namespace App\Models;

use App\Models\User;
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
}

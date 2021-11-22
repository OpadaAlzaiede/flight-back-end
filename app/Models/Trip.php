<?php

namespace App\Models;

use Carbon\Carbon;
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

        return $this->belongsToMany(User::class)->withPivot('seat');
    }

    public function governorate() {

        return $this->belongsTo(Governorate::class);
    }

    public function driver() {

        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getActiveTrips() {

        return self::where('starts_at', '>=', Carbon::now)->get();
    }

    public function occupiedSeats() {

        $occupiedSeats = [];

        $passengers = $this->users()->get();

        foreach($passengers as $passenger) {
            array_push($occupiedSeats, $passenger->pivot->seat);
        }

        return $occupiedSeats;
    }
}

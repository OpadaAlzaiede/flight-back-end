<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Comment;
use App\Models\Attachment;
use App\Models\Governorate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'starts_at',
        'arrives_at',
        'governorate_id',
        'departure',
        'destination',
        'details',
        'number_of_seats',
        'estimated_time',
        'car_plate',
        'cost'
    ];

    protected $casts = [
        'starts_at' => 'datetime'
    ];

    public function users() {

        return $this->belongsToMany(User::class)->withPivot('seat', 'is_arrived');
    }

    public function governorate() {

        return $this->belongsTo(Governorate::class);
    }

    public function driver() {

        return $this->belongsTo(User::class, 'user_id');
    }

    public function attachments() {

        return $this->morphMany(Attachment::class, 'attachable');
    }

    public static function getActiveTrips() {

        return self::where([['starts_at', '>=', Carbon::now()], ['status', 0]])->get();
    }

    public function cancel() {
        
        $this->status = 1;
        $this->save();
    }

    public function activate() {

        $this->status = 0;
        $this->save();
    }

    public function comments() {

        return $this->hasMany(Comment::class);
    }

    public function isCanceled() {

        return $this->status === 1;
    }

    public function getFreeSeats() {

        $numberOfSeats = $this->number_of_seats;
        $freeSeats = [];
        $occupiedSeats = $this->getOccupiedSeats();

        for($i = 1; $i <= $numberOfSeats; $i++) {
            if(in_array($i, $occupiedSeats))
                continue;

            array_push($freeSeats, $i);
        }

        return $freeSeats;
    }

    public function getOccupiedSeats() {

        $occupiedSeats = [];

        $passengers = $this->users()->get();

        foreach($passengers as $passenger) {
            array_push($occupiedSeats, $passenger->pivot->seat);
        }

        return $occupiedSeats;
    }

    public function isFree($seat) {

        return in_array($seat, $this->getFreeSeats());
    }

    public static function getArrivedTrips() {

        return self::where('arrives_at', '>=', Carbon::now())->get();
    }
}

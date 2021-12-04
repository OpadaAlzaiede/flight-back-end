<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'starts_at' => $this->starts_at,
            'number_of_seats' => $this->number_of_seats,
            'departure' => $this->departure,
            'destination' => $this->destination,
            'estimated_time' => $this->estimated_time,
            'car_plate' => $this->car_plate,
            'cost' => $this->cost,
            'details' => $this->details,
            'status' => $this->status,
            'driver' => $this->driver,
            'governorate' => $this->governorate,
            'passengers' => $this->users,
            'comments' => CommentResource::collection($this->comments),
            'occupied_seats' => $this->getOccupiedSeats(),
            'free_seats' => $this->getFreeSeats()
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Role;
use App\Http\Resources\TripResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AttachmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'father_name' => $this->father_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'photo' => 'storage/'. $this->id_photo,
            'role' => $this->role,
            'trips' => $this->role_id === Role::getRolesArray()['PASSENGER']
                            ? TripResource::collection($this->trips)
                            : TripResource::collection($this->tripsAsDriver),
            'attachmants' => AttachmentResource::collection($this->attachments)
        ];
    }
}

<?php

namespace App\Models;

use App\Models\Trip;
use App\Models\User;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_id',
        'content'
    ];

    public function user() {

        return $this->belongsTo(User::class);
    }

    public function trip() {

        return $this->belongsTo(Trip::class);
    }

    public function attachments() {

        return $this->morphMany(Attachment::class, 'attachable');
    }
}

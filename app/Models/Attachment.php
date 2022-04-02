<?php

namespace App\Models;

use App\Models\Trip;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;

    public const MORPHS = [
        1 => User::class,
        2 => Trip::class,
        3 => Comment::class
    ];

    protected $table = 'attachments';

    protected $fillable = [
        'attachable_id',
        'attachable_type',
        'date'
    ];

    public function attachable() {

        return $this->morphTo();
    }
}

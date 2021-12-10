<?php


namespace App\Traits;


use Carbon\Carbon;
use App\Models\Attachment;
use Modules\Pledge\Entities\Pledge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait Attachments
{

    public function storeAttachment($file, $attachable_id, $attachable_type, $user_id = null) {

        $split_directory = explode('\\', $attachable_type);
        $directory = $split_directory[count($split_directory)-1];
        
        try {
            $path = Storage::disk('public')->putFileAs(
                $directory, $file, time() . ' '. $file->getClientOriginalName()
            );
            $attachment = new Attachment();
            $attachment->url = $path;
            $attachment->attachable_type = $attachable_type;
            $attachment->attachable_id = $attachable_id;
            $attachment->date = Carbon::now();
            $attachment->user_id = $user_id;
            $attachment->save();
            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public function storeAvatar($file, $user) {

        try {
            $path = Storage::disk('public')->putFileAs(
                'avatars', $file, time() . ' '. $file->getClientOriginalName()
            );
            $user->id_photo = $path;
            $user->save();
            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }
}

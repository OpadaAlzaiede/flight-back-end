<?php


namespace App\Traits;


use App\Models\Attachment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\Pledge\Entities\Pledge;

trait Attachments
{
    protected $directory = 'avatars';

    public function storeAttachment($file, $user)
    {
        try {
            $path = Storage::disk('public')->putFileAs(
                $this->directory, $file, time().'_'.$file->getClientOriginalName()
            );
          
            $user->id_photo = $path;
            $user->save();

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }
}

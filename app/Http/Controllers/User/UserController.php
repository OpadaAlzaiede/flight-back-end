<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Traits\Attachments;
use App\Traits\ApiResponser;
use App\Traits\JSONResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;

class UserController extends Controller
{
    use JSONResponse, ApiResponser, Attachments;

    public function __construct() {

        $this->setResource(UserResource::class);
    }

    public function changePassword(ChangePasswordRequest $request) {

        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return $this->error(401, 'Credentials not match');
        }

        $user = Auth::user();

        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->resource($user);
    }

    public function profile() {

        return new UserResource(Auth::user());
    }

    public function updateProfile(UpdateProfileRequest $request) {
      
        $user = Auth::user();

        $user->update($request->except('id_photo'));

        if($request->id_photo)
            Storage::disk('public')->delete($attachment->url);
            $this->storeAvatar($request->id_photo, $user);

        return new UserResource($user)
    }
}

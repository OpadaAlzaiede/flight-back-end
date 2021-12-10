<?php

namespace App\Http\Controllers\User;

use App\Traits\ApiResponser;
use App\Traits\JSONResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ChangePasswordRequest;

class UserController extends Controller
{
    use JSONResponse, ApiResponser;

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
}

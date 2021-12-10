<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\Pagination;
use App\Traits\Attachments;
use App\Traits\ApiResponser;
use App\Traits\JSONResponse;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    use ApiResponser, JSONResponse, Pagination, Attachments;

    public function __construct() {

        $this->setResource(UserResource::class);
    }
    
    public function register(RegisterRequest $request) {


        $user = User::create($request->all());

        $user->password = Hash::make($user->password);
        
        if($request->id_photo)
            $this->storeAttachment($request->id_photo, $user);

        if($request->hasFile('attachments')) {
            foreach($request->file('attachments') as $file) {
                $is_stored = $this->storeAttachment($file, $user->id, User::class);
                if(!$is_stored)
                    return $this->error(300, "something wen't wrong !");
            }
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token
        ]);

    }

    public function login(LoginRequest $request) {

        if (!Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            return $this->error(401, 'Credentials not match');
        }
        
        $user = auth()->user();

        return $this->success([
            'user' => new UserResource($user),
            'token' => auth()
                ->user()
                ->createToken('Login API Token')
                ->plainTextToken
        ], 'You Have Logged in Successfully');
    }

    public function logout() {

        auth()->user()->tokens()->delete();

        return $this->success([], 'You Have Successfully Logged out');
    }
}

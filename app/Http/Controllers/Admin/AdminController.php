<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Traits\ApiResponser;
use App\Traits\JSONResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetUserDataRequest;
use App\Http\Resources\UserResource;
use Spatie\QueryBuilder\QueryBuilder;

class AdminController extends Controller
{
    use ApiResponser, JSONResponse;

    public function __construct(Request $request)
    {
        $this->perPage = $request->perPage ?? 10;
        $this->page = $request->page ?? 1;
    }

    public function getAllUsers() {

        $users = QueryBuilder::for(User::class)
                              ->allowedFilters(['role_id', 'is_approved'])
                              ->defaultSort('-id')
                              ->paginate($this->perPage, ['*'], 'page', $this->page);

        return UserResource::collection($users);
    }
    
    public function resetUserData(ResetUserDataRequest $request, $id) {

        $user = User::find($id);

        if(!$user)
            return $this->error(404, 'Not Found !');
        
        $user->update($request->all());

        return new UserResource($user);
    }

    public function approveUser($id) {

        $user = User::find($id);
        $user->approve();

        return new UserResource($user);
    }

    public function disApproveUser($id) {

        $user = User::find($id);
        $user->disApprove();

        return new UserResource($user);
    } 
}

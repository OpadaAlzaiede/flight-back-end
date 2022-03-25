<?php

namespace App\Http\Controllers\Role;

use App\Models\Role;
use App\Traits\Pagination;
use App\Traits\ApiResponser;
use App\Traits\JSONResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    use ApiResponser, JSONResponse, Pagination;

    public function __construct()
    {
        $this->setResource(RoleResource::class);
    }

    public function index() {

        $adminRoleId = Role::getRolesArray()['ADMIN'];

        return Role::where('id', '!=', $adminRoleId)->get();
    }
}

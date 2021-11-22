<?php

namespace App\Http\Controllers\Trip;

use App\Models\Trip;
use App\Traits\Pagination;
use App\Traits\ApiResponser;
use App\Traits\JSONResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;

class TripController extends Controller
{
    use ApiResponser, JSONResponse, Pagination;

    protected $perPage;
    protected $page;
    protected $keyword;

    public function __construct(Request $request) {
        $this->setResource(TripResource::class);
        $this->perPage = $request->perPage ?? 10;
        $this->page = $request->page ?? 1;
        $this->keyword = $request->keyword ?? '';
    }

    public function index() {

        $trips = Trip::with('users', 'driver', 'governorate')
                    ->where('details', 'like', '%'. $this->keyword .'%')
                    ->paginate($this->perPage, ['*'], 'page', $this->page);

        return $this->collection($trips);
    }

}

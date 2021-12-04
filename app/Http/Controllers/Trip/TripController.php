<?php

namespace App\Http\Controllers\Trip;

use App\Models\Trip;
use App\Traits\Pagination;
use App\Traits\ApiResponser;
use App\Traits\JSONResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;

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

    public function show($id) {

        $trip = Trip::find($id);

        if(!$trip)
            return $this->error(404, 'Not Found !');
        
        return $this->resource($trip);
    }

    public function store(StoreTripRequest $request) {
        
        $trip = Trip::create($request->all());

        $trip->user_id = Auth::id();
        $trip->status = 0;
        $trip->save();

        return $this->resource($trip);
    }

    public function update(UpdateTripRequest $request, $id) {

        if(!$this->checkIfAuthorized($id))
            return $this->error(302, 'Unauthorized');

        $trip = Trip::find($id);

        if(!$trip)
            return $this->error(404, 'Not Found');

        $trip->update($request->all());

        return $this->resource($trip);
    }

    public function cancel($id) {

        if(!$this->checkIfAuthorized($id))
            return $this->error(302, 'Unauthorized');

        $trip = Trip::find($id);

        if(!$trip)
            return $this->error(404, 'Not Found');

        $trip->cancel();

        return $this->success([], 'Canceled Successfully');
    }

    private function checkIfAuthorized($id) {
        return
             Auth::id() === $id &&
             Auth::user()->role_id = Role::getRolesArray()['DRIVER'];
    }

}

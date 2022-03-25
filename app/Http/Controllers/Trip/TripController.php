<?php

namespace App\Http\Controllers\Trip;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Trip;
use App\Traits\Pagination;
use App\Traits\Attachments;
use App\Traits\ApiResponser;
use App\Traits\JSONResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use Spatie\QueryBuilder\QueryBuilder;

class TripController extends Controller
{
    use ApiResponser, JSONResponse, Pagination, Attachments;

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

        $trips = QueryBuilder::for(Trip::class)
                            ->allowedIncludes(['users', 'driver', 'governorate'])
                            ->allowedFilters(['id', 'status', 'details', 'departure', 'starts_at', 'governorate.name'])
                            ->defaultSort('-id')
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

        if(!Auth::user()->isDriver())
            return $this->error(401, 'Unauthorized');
        
        $trip = Trip::create($request->all());

        if($request->hasFile('attachments')) {
            foreach($request->file('attachments') as $file) {
                $is_stored = $this->storeAttachment($file, $trip->id, Trip::class, Auth::id());
                if(!$is_stored)
                    return $this->error(300, "something wen't wrong !");
            }
        }

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

    public function destroy($id) {

        if(!$this->checkIfAuthorized($id))
            return $this->error(302, 'Unauthorized');

        $trip = Trip::find($id);

        if(Carbon::now() > $trip->starts_at)
            return $this->success([], "Can't Delete trip");

        if(!$trip)
            return $this->error(404, 'Not Found');

        foreach($trip->users()->get() as $user) {
            $user->pivot->delete();
        }
        
        $trip->delete();

        return $this->success([], 'Deleted Successfully');

    }

    // cancel trip
    public function cancel($id) {

        if(!$this->checkIfAuthorized($id))
            return $this->error(302, 'Unauthorized');

        $trip = Trip::find($id);

        if(!$trip)
            return $this->error(404, 'Not Found');

        if(Carbon::now() > $trip->starts_at)
            return $this->success([], "Can't cancel trip");

        $trip->cancel();

        return $this->success([], 'Canceled Successfully');
    }

    // reactivate trip
    public function activate($id) {

        if(!$this->checkIfAuthorized($id))
            return $this->error(302, 'Unauthorized');

        $trip = Trip::find($id);

        if(!$trip)
            return $this->error(404, 'Not Found');
            
        $trip->activate();

        return $this->success([], 'Activated Successfully');
    }

    // reserve in a trip
    public function reserve(Request $request, $id) {

        if(Auth::user()->trips()->where('trip_id', '!=', $id)->count() > 0)
            return $this->error(300, 'Already in a trip !');

        $trip = Trip::find($id);

        if(!$trip || $trip->starts_at < Carbon::now())
            return $this->error(404, 'Not Found !');

        if($trip->isCanceled())
            return $this->error(300, 'Trip is canceled !');

        if(is_array($request->seat)) {
            foreach($request->seat as $se) {
                if(!$trip->isFree($se))
                    continue;
                $trip->users()->attach([Auth::id()=>[
                    'seat' => $se,
                    'date' => Carbon::now(),
                    'is_arrived' => 0
                ]]);
            }
        }else {
            if($trip->isFree($$request->seat))  
                $trip->users()->attach([Auth::id()=>[
                    'seat' => $request->seat,
                    'date' => Carbon::now(),
                    'is_arrived' => 0
                ]]);
        }

        return $this->resource($trip);
    }

    // leave trip
    public function leave($id) {

        $trip = Trip::find($id);

        if(Carbon::now() > $trip->starts_at)
            return $this->success([], "Can't leave trip");

        $trip->users()->detach(Auth::id());

        return $this->resource($trip);
    }

    // unreserve a seat
    public function unReserveSeat($id, $seat) {

        $trip = Trip::find($id);

        if(!$trip)
            return $this->error(404, 'Not Found !');

        if(Carbon::now() > $trip->starts_at)
            return $this->success([], "Can't leave trip");

        $user = $trip->users()->where('user_id', Auth::id())->first();

        $user->pivot->where('seat', $seat)->delete();

        return $this->success([], 'seat unreserved !');
    }

    // check as arrived
    public function checkAsArrived($id) {

        $trip = Trip::find($id);
        $user = $trip->users()->where('user_id', Auth::id())->first();

        $user->pivot->is_arrived = 1;
        $user->pivot->save();
        
        return $this->success([], 'Checked successfully!');
    }

    private function checkIfAuthorized($id) {

        return
             Auth::id() === Trip::find($id)->user_id &&
             Auth::user()->role_id = Role::getRolesArray()['DRIVER'];
    }

}

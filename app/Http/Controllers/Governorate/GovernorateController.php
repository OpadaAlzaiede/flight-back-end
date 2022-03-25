<?php

namespace App\Http\Controllers\Governorates;

use App\Traits\Pagination;
use App\Models\Governorate;
use App\Traits\ApiResponser;
use App\Traits\JSONResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\GovernorateResource;

class GovernorateController extends Controller
{
    use ApiResponser, JSONResponse, Pagination;

    protected $perPage;
    protected $page;
    protected $keyword;

    public function __construct(Request $request) {
        $this->setResource(GovernorateResource::class);
        $this->perPage = $request->perPage ?? 10;
        $this->page = $request->page ?? 1;
        $this->keyword = $request->keyword ?? '';
    }

    public function index() {

        $governorates = QueryBuilder::for(Governorate::class)
                                    ->allowedFilters(['name'])
                                    ->defaultSort(['id'])
                                    ->paginate($this->perPage, ['*'], 'page', $this->page);

        return $this->collection($governorates);
    }

    public function show($id) {

        $governorate = Governorate::find($id);

        if(!$governorate)
            return $this->error(404, 'Not Found !');
        
        return $this->resource($governorate);
    }
}

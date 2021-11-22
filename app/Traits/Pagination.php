<?php


namespace App\Traits;

use Illuminate\Http\Request;


trait Pagination
{
    public $model_perPage = 20;
    public $model_page = 1;

    public function checkPerPageValue(Request $request)
    {
        return $request->keyword ? $this->model_perPage : $request->perPage;
    }

    public function checkPageValue(Request $request)
    {
        return $request->keyword ? $this->model_page: $request->page;
    }
}

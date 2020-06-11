<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\RequestType;
use Illuminate\Http\Response;

class RequestTypesApiController extends Controller
{
    public function index()
    {
        $data = RequestType::all();

        return response()->json($data)->setStatusCode(Response::HTTP_OK);
    }
}

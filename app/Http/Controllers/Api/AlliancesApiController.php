<?php

namespace App\Http\Controllers\Api;

use App\Alliance;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AlliancesApiController extends Controller
{
    public function index() : JsonResponse
    {
        return response()->json(Alliance::all())->setStatusCode(Response::HTTP_OK);
    }
}

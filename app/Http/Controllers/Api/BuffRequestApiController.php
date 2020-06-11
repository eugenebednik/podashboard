<?php

namespace App\Http\Controllers\Api;

use App\BuffRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BuffRequests\CreateBuffRequestRequest;
use App\Http\Requests\Api\BuffRequests\UpdateBuffRequestRequest;
use App\RequestType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BuffRequestApiController extends Controller
{
    public function index()
    {
        $data = BuffRequest::query()->where('outstanding', true)->get();

        return response()->json($data)->setStatusCode(Response::HTTP_OK);
    }

    public function show(Request $request, int $id)
    {
        $data = BuffRequest::find($id);

        if ($data) {
            return response()->json($data)->setStatusCode(Response::HTTP_OK);
        }

        return response()->json(['errors' => __('Not found.')])->setStatusCode(Response::HTTP_NOT_FOUND);
    }

    public function create(CreateBuffRequestRequest $request)
    {
        $buffRequest = new BuffRequest();
        $buffRequest->fill($request->all());
        $buffRequest->requestType()->associate(RequestType::findOrFail($request->input('request_type_id')));
        $buffRequest->save();

        return response()->json($buffRequest)->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateBuffRequestRequest $request, int $id)
    {
        /** @var BuffRequest $buffRequest */
        $buffRequest = BuffRequest::find($id);

        if ($buffRequest) {
            $buffRequest->fill($request->all());
            $buffRequest->requestType()->associate(RequestType::findOrFail($request->input('request_type_id')));
            $buffRequest->handledBy()->associate(Auth::user());
            $buffRequest->save();
        }
    }

    public function destroy(int $id)
    {
        $buffRequest = BuffRequest::findOrFail($id);
        $buffRequest->delete();

        return response()->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}

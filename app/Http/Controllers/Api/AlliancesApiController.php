<?php

namespace App\Http\Controllers\Api;

use App\Alliance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateAllianceRequest;
use App\Http\Requests\Api\UpdateAllianceRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AlliancesApiController extends Controller
{
    public function index() : JsonResponse
    {
        return response()->json(Alliance::all())->setStatusCode(Response::HTTP_OK);
    }

    public function show(int $id)
    {
        $alliance = Alliance::findOrFail($id);

        return response()->json($alliance)->setStatusCode(Response::HTTP_OK);
    }

    public function update(UpdateAllianceRequest $request, int $id) : JsonResponse
    {
        /** @var Alliance $alliance */
        $alliance = Alliance::findOrFail($id);
        $alliance->name = $request->input('name');
        $alliance->save();

        return response()->json($alliance)->setStatusCode(Response::HTTP_OK);
    }

    public function store(CreateAllianceRequest $request) : JsonResponse
    {
        /** @var User $alliance */
        $alliance = new Alliance([
            'name' => $request->input('name'),
        ]);

        $alliance->save();

        return response()->json($alliance)->setStatusCode(Response::HTTP_CREATED);
    }

    public function destroy(int $id) : JsonResponse
    {
        $alliance = Alliance::findOrFail($id);
        $alliance->delete();

        return response()->json([])->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}

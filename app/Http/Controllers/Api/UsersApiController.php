<?php

namespace App\Http\Controllers\Api;

use App\Alliance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateUserRequest;
use App\Http\Requests\Api\UpdateUserRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UsersApiController extends Controller
{
    public function index() : JsonResponse
    {
        $users = User::with('alliance')->get();

        return response()->json($users)->setStatusCode(Response::HTTP_OK);
    }

    public function show(int $id)
    {
        $user = User::findOrFail($id);

        return response()->json($user)->setStatusCode(Response::HTTP_OK);
    }

    public function update(UpdateUserRequest $request, int $id) : JsonResponse
    {
        /** @var User $user */
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->alliance()->associate(Alliance::findOrFail($request->input('alliance_id')));
        $user->email = $request->input('email');
        $user->email_verified_at = Carbon::now();
        $user->is_admin = $request->input('is_admin');
        $user->active = $request->input('active');
        $user->save();

        return response()->json($user)->setStatusCode(Response::HTTP_OK);
    }

    public function store(CreateUserRequest $request) : JsonResponse
    {
        /** @var User $user */
        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make($request->input('password')),
            'is_admin' => $request->input('is_admin'),
            'active' => $request->input('active'),
        ]);

        $user->alliance()->associate(Alliance::findOrFail($request->input('alliance_id')));
        $user->save();

        return response()->json($user)->setStatusCode(Response::HTTP_CREATED);
    }

    public function destroy(int $id) : JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([])->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}

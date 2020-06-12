<?php

namespace App\Http\Controllers\Api;

use App\BuffRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateDoneRequest;
use Illuminate\Http\Response;

class DoneApiController extends Controller
{
    public function store(CreateDoneRequest $request)
    {
        if (BuffRequest::where('discord_snowflake', $request->input('discord_snowflake'))
            ->where('outstanding', true)
            ->count() > 0)
        {
            BuffRequest::where('discord_snowflake', $request->input('discord_snowflake'))
                ->where('outstanding', true)
                ->update([
                    'outstanding' => false,
                ]);

            return response()->json(['success' => true])->setStatusCode(Response::HTTP_CREATED);
        }

        return response()->json(['success' => false])->setStatusCode(Response::HTTP_NOT_FOUND);
    }
}

@extends('layouts.app')

@section('content')
    <user-roles
        :roles="{{ json_encode($roles) }}"
        :server-id="{{ $server->id }}"
        :token="'{{ \Illuminate\Support\Facades\Auth::user()->api_token }}'"
    ></user-roles>
@endsection

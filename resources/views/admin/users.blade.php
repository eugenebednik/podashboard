@extends('layouts.app')

@section('content')
    <users
        :users="{{ json_encode($users) }}"
        :server-id="{{ $server->id }}"
        :token="'{{ \Illuminate\Support\Facades\Auth::user()->api_token }}'"
        :dashboard-url="'{{ url('dashboard') }}'"
        :self-id="{{ \Illuminate\Support\Facades\Auth::user()->id }}"
    ></users>
@endsection

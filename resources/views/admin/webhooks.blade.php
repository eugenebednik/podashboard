@extends('layouts.app')

@section('content')
    <webhooks
        :server-id="{{ $server->id }}"
        :token="'{{ \Illuminate\Support\Facades\Auth::user()->api_token }}'"
        :dashboard-url="'{{ url('dashboard') }}'"
    ></webhooks>
@endsection

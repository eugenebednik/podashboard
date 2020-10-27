@extends('layouts.app')

@section('content')
    <dashboard
        :server-id="{{ $server->id }}"
        :user-id="{{ $userId }}"
        :token="'{{ $token }}'"
    ></dashboard>
@endsection

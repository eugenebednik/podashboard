@extends('layouts.app')

@section('content')
    <dashboard
        :server-id="{{ $server->id }}"
        :user-id="{{ $userId }}"
        :is-user-admin="{{ \Illuminate\Support\Facades\Auth::user()->isAdminOfServer($server) ? 'true' : 'false' }}"
        :token="'{{ $token }}'"
        :webhook-url="'{{ route('admin.webhooks.index') }}'"
    ></dashboard>
@endsection

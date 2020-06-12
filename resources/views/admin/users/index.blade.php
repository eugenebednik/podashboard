@extends('layouts.app')

@section('content')
    <users-index
        token="{{$token}}"
        alliances="{{$alliances}}"
        user="{{\Illuminate\Support\Facades\Auth::user()->id}}"
        served="{{json_encode($usersServed)}}"
    ></users-index>
@endsection

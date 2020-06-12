@extends('layouts.app')

@section('content')
    <alliances-index
        token="{{$token}}"
        served="{{json_encode($alliancesServed)}}"
    ></alliances-index>
@endsection

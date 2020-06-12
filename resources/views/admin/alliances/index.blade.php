@extends('layouts.app')

@section('content')
    <alliances-index
        token="{{$token}}"
        served="{{json_encode($alliancesServed)}}"
        alliance="{{$thisUserAllianceId}}"
    ></alliances-index>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">{{__('Dashboard')}}</div>

                <div class="card-body">
                    {{ __("We're sorry, but a server ID is required to login to the Dashboard.") }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">{{__('Dashboard')}}</div>

                    <div class="card-body">
                        {{"We're sorry, but your server account has not been activated in Discord. Please contact the management to activate it."}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

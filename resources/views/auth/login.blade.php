@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach($errors->all() as $error)
                <div class="alert alert-warning" role="alert">
                    {{ $error }}
                </div>
            @endforeach
            <div class="card">
                <div class="card-header">
                    <h5 class="text-muted">{{ __('Login') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <a class="btn btn-info" href="{{ route('login.index') }}">
                                {{ __('Login with Discord') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

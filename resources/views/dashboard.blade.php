@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Unfulfilled Requests</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Request Type</th>
                            <th scope="col">Handle</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($outstandingBuffRequests as $outstandingBuffRequest)
                        <tr>
                            <th scope="row">{{$outstandingBuffRequest->id}}</th>
                            <td>{{$outstandingBuffRequest->user_name}}</td>
                            <td>{{$outstandingBuffRequest->requestType->name}}</td>
                            <td><a href="{{ url("fulfill/$outstandingBuffRequest->id") }}" class="btn btn-primary" role="button">{{ __('Fulfill') }}</a></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>

                <div class="card-footer">
                    {{ $outstandingBuffRequests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        setTimeout(function(){
            window.location.reload();
        }, 5000);
    </script>
@endsection

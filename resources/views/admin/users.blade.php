@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @foreach($errors->all() as $error)
                    <div class="alert alert-warning" role="alert">
                        {{ $error }}
                    </div>
                @endforeach
                <div class="card">
                    <div class="card-header">{{ __('Server PO Performance Report') }}</div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Number of Requests Fulfilled') }}</th>
                                <th scope="col">{{ __('Average Time Per Session') }}</th>
                                <th scope="col">{{ __('Total Time Spent Serving') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $user)
                                <tr>
                                    <td>{{$user['name']}}</td>
                                    <td>{{$user['count']}}</td>
                                    <td>{{$user['average_time_per_session']}}</td>
                                    <td>{{$user['total_time_spent_serving']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>

                    <div class="card-footer">
                        <div class="float-right">
                            <a class="btn btn-outline-secondary" href="{{ url('dashboard') }}">&larr; Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

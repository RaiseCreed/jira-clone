@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <p>{{ __('You are logged in as an admin!') }}</p>
                    @endif

                    @if(auth()->user()->isCustomer())
                        <p>{{ __('You are logged in as an customer!') }}</p>
                    @endif

                    @if(auth()->user()->isWorker())
                        <p>{{ __('You are logged in as an worker!') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

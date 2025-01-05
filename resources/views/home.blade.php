@extends('layouts.app')

@section('content')
<div class="container">
    @if(auth()->user()->isAdmin())
    <p>{{ __('You are logged in as an admin!') }}</p>
    @endif

    @if(auth()->user()->isCustomer())
    <p>{{ __('You are logged in as an customer!') }}</p>
    @endif

    @if(auth()->user()->isWorker())
    <p>{{ __('You are logged in as an worker!') }}</p>
    @endif
    <div class="col-md-4 float-md-end sticky-md-top ms-md-2 mb-2 z-1">
        <div class="card">
            <div class="card-header" data-bs-toggle="collapse" href="#statistics" role="button" aria-expanded="true"
                aria-controls="statistics">Statistics
            </div>
            <div class="collapse show" id="statistics">
                <div class="card-body">
                    aaaaaaaaaaaaaaaa
                </div>
            </div>

        </div>

    </div>
    <div class="col-md-8">
        <div class=" card">
            <div class="card-header">{{ __('Dashboard') }}</div>

            <div class="card-body" style="height: 2000px">
                bbbbbbbb


            </div>
        </div>
    </div>
</div>
@endsection
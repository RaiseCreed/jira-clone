@extends('layouts.app')

@section('content')
<div class="container">
    @if(auth()->user()->isAdmin())
    <p>{{ __('You are logged in as an admin!') }}</p>
    @endif

    @if(auth()->user()->isCustomer() || auth()->user()->isWorker())
    <div class="col-md-4 float-md-end sticky-md-top ms-md-2 mb-2 z-1">
        <div class="card">
            <div class="card-header" data-bs-toggle="collapse" href="#statistics" role="button" aria-expanded="true"
                aria-controls="statistics">Statistics
            </div>
            <div class="collapse show" id="statistics">
                <div class="card-body">
                    <div class="mb-2">
                        <div class="mb-2 fs-5 text-center">Open tickets</div>
                        <div class="row justify-content-center">
                            <div class="nj-ticket-indicator">
                                <div class="nj-ticket-circle sla-0">4</div>
                                <div class="mb-2 text-nowrap">SLA-0</div>
                            </div>
                            <div class="nj-ticket-indicator">
                                <div class="nj-ticket-circle sla-1">3</div>
                                <div class="mb-2 text-nowrap">SLA-1</div>
                            </div>
                            <div class="nj-ticket-indicator">
                                <div class="nj-ticket-circle sla-2">2</div>
                                <div class="mb-2 text-nowrap">SLA-2</div>
                            </div>
                            <div class="nj-ticket-indicator">
                                <div class="nj-ticket-circle sla-3">1</div>
                                <div class="mb-2 text-nowrap">SLA-3</div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-8">
                            <div class="mb-2 fs-5 text-center">Ticket due times</div>
                            <div class="mb-2 nj-ticket-due-time-indicator">
                                <div class="">Overdue</div>
                                <div class="nj-ticket-circle overdue">4</div>
                            </div>
                            <div class="mb-2 nj-ticket-due-time-indicator">
                                <div class="">Today</div>
                                <div class="nj-ticket-circle today">4</div>
                            </div>
                            <div class="mb-2 nj-ticket-due-time-indicator">
                                <div class="">Tomorrow</div>
                                <div class="nj-ticket-circle tomorrow">4</div>
                            </div>
                            <div class="nj-ticket-due-time-indicator">
                                <div class="">Later</div>
                                <div class="nj-ticket-circle rest">4</div>
                            </div>
                        </div>
                    </div>
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
    @endif
</div>
@endsection
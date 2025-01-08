@extends('layouts.app')

@section('content')
<div class="container">

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
            <div class="card-header">{{ __('My tickets') }}</div>
            <div class="card-body">
                <a href="{{ route('tickets.create') }}" class="col-12 col-sm-auto btn btn-primary mt-3 float-end"><i
                        class="bi bi-plus me-1"></i>Add</a>
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ticket name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Priority</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($tickets as $ticket)
                        <tr>
                            <th scope="row">{{$i}}</th>
                            <td>{{$ticket->title}}</td>
                            <td>{{$ticket->category->name}}</td>
                            <td>{{$ticket->priority->name}}</td>
                            <td>{{$ticket->status->name}}</td>
                            <td>
                                <a href="{{route('tickets.show', $ticket->id)}}" class="btn btn-primary">Show details</a>
                            </td>
                        </tr>
                        @php
                        $i++;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
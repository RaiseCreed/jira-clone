@extends('layouts.app')
@include('statistics.statistics-admin')
@include('statistics.statistics-customer')
@include('statistics.statistics-worker')

@section('content')
<div class="container">
    <div class="col-md-4 float-md-end sticky-md-top ms-md-2 mb-2 z-1">
        <div class="card">
            <div class="card-header" data-bs-toggle="collapse" href="#statistics" role="button" aria-expanded="true"
                aria-controls="statistics">Statistics
            </div>
            <div class="collapse show" id="statistics">
                <div class="card-body">
                    @if(auth()->user()->isAdmin())
                    @yield('statistics-admin')
                    Bląd dzielenia przez ogórek. Zainstaluj ponownie wszechświat i rebootuj.
                    @elseif(auth()->user()->isWorker())
                    @yield('statistics-worker')
                    @elseif(auth()->user()->isCustomer())
                    @yield('statistics-customer')
                    @else
                    Bląd dzielenia przez ogórek. Zainstaluj ponownie wszechświat i rebootuj.
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class=" card">
            <div class="card-header">{{ __('Assigned tickets') }}</div>
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
                            <th scope="col">Ticket name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Priority</th>
                            <th scope="col">Status</th>
                            <th scope="col">Issued by</th>
                            <th scope="col">Deadline</th>
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
                            <td>{{$ticket->owner->name}}</td>
                            <td>{{$ticket->deadline}}</td>
                            <td>
                                <a href="{{route('tickets.show', $ticket->id)}}" class="btn btn-primary">Show
                                    details</a>
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
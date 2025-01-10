@extends('layouts.app')
@include('statistics.statistics-admin')
@include('statistics.statistics-customer')
@include('statistics.statistics-worker')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 order-1 order-md-2">
            <div class="card">
                <div class="card-header" data-bs-toggle="collapse" href="#statistics" role="button" aria-expanded="true"
                    aria-controls="statistics">Statistics
                </div>
                <div class="collapse show" id="statistics">
                    <div class="card-body">
                        @if(auth()->user()->isAdmin())
                        @yield('statistics-admin')
                        @elseif(auth()->user()->isWorker())
                        @yield('statistics-worker')
                        @elseif(auth()->user()->isCustomer())
                        @yield('statistics-customer')
                        @else
                        Błąd dzielenia przez ogórek. Zainstaluj ponownie wszechświat i rebootuj.
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="col-md-8 order-2 order-md-1">
            <div class=" card">

                @if(auth()->user()->isAdmin())
                <div class="card-header">{{ __('Unassigned tickets') }}</div>
                @elseif(auth()->user()->isWorker())
                <div class="card-header">{{ __('Assigned tickets') }}</div>
                @elseif(auth()->user()->isCustomer())
                <div class="card-header">{{ __('My tickets') }}</div>
                @endif

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form method="GET" action="{{ route('home') }}" class="mb-4">
                        <div class="nj-button-row justify-content-start">
                            <div class="col-auto">
                                <input type="text" name="title" class="form-control" placeholder="Ticket name"
                                    value="{{ request('title') }}">
                            </div>
                            <div class="col-auto">
                                <select name="category" class="form-control">
                                    <option value="">Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category')==$category->id ?
                                        'selected' :
                                        '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <select name="priority" class="form-control">
                                    <option value="">Priority</option>
                                    @foreach($priorities as $priority)
                                    <option value="{{ $priority->id }}" {{ request('priority')==$priority->id ?
                                        'selected' :
                                        '' }}>
                                        {{ $priority->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <select name="status" class="form-control">
                                    <option value="">Status</option>
                                    @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ request('status')==$status->id ? 'selected' :
                                        ''
                                        }}>
                                        {{ $status->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <input type="date" name="deadline" class="form-control"
                                    value="{{ request('deadline') }}">
                            </div>
                            <div class="col-auto">
                                {{request('statuses')}}
                                <select name="worker" class="form-control">
                                    <option value="">Worker</option>
                                    <option value="unassigned" {{ request('worker')=='unassigned' ? 'selected' : '' }}>
                                        Not assigned</option>
                                    @foreach($workers as $worker)
                                    <option value="{{ $worker->id }}" {{ request('worker')==$worker->id ? 'selected' :
                                        ''}}>
                                        {{ $worker->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
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
                                    @if(auth()->user()->isAdmin())
                                    <th scope="col">Assigned worker</th>
                                    @endif
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $key => $ticket)
                                <tr>
                                    <th scope="row">{{ ($tickets->currentpage()-1) * $tickets->perpage() + $key + 1 }}
                                    </th>
                                    <td>{{$ticket->title}}</td>
                                    <td>{{$ticket->category->name}}</td>
                                    <td>{{$ticket->priority->name}}</td>
                                    <td>{{$ticket->status->name}}</td>
                                    <td>{{$ticket->owner->name}}</td>
                                    <td>{{$ticket->deadline}}</td>
                                    @if(auth()->user()->isAdmin())
                                    <td>{{($ticket->worker) ? $ticket->worker->name : "Not assigned" }}</td>
                                    @endif
                                    <td>
                                        <a href="{{route('tickets.show', $ticket->id)}}" class="btn btn-primary">Show
                                            details</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $tickets->appends($_GET)->links() }}
                </div>
            </div>
        </div>
        @isset($qoute)
        <div class="row">
            <div class="col-md-8 mt-4">
                <div class="card text-center">
                    <div class="card-header">Random Quote</div>
                    <div class="card-body">
                        "{{$quote->quote}}" ~ {{$quote->author}}
                    </div>
                </div>
            </div>
        </div>
        @endisset
    </div>
</div>
@endsection
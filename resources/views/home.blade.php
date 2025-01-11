@extends('layouts.app')
@include('statistics.statistics-admin')
@include('statistics.statistics-customer')
@include('statistics.statistics-worker')

@section('content')
<div class="container">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="row gap-md-0 gap-3">
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
                    <form method="GET" action="{{ route('home') }}" class="mb-3">
                        <div class="nj-button-row justify-content-start">
                            <div class="col-auto">
                                <input type="text" name="title" class="form-control" placeholder="Ticket name"
                                    value="{{ request('title') }}">
                            </div>
                            <div class="col-auto">
                                <select name="category" class="form-control">
                                    <option value="" disabled selected hidden>Category</option>
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
                                    <option value="" disabled selected hidden>Priority</option>
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
                                    <option value="" disabled selected hidden>Status</option>
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

                            @if(auth()->user()->isAdmin())
                            <div class="col-auto">
                                {{request('statuses')}}
                                <select name="worker" class="form-control">
                                    <option value="" disabled selected hidden>Worker</option>
                                    <option value="">All</option>
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
                            @endif

                        </div>
                        <div class="nj-button-row mt-3 justify-content-end">
                            <a href="{{route('home')}}"><span type="reset" class="nj-button-secondary">Clear</span></a>
                            <button type="submit" class="nj-button-primary">Filter</button>
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
                                    @if(auth()->user()->isAdmin() || auth()->user()->isWorker())
                                    <th scope="col">Issued by</th>
                                    @endif
                                    <th scope="col">Deadline</th>
                                    @if(auth()->user()->isAdmin())
                                    <th>Assigned worker</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @foreach($tickets as $key => $ticket)
                                <tr class="align-middle">
                                    <th scope="row">{{ ($tickets->currentpage()-1) * $tickets->perpage() + $key + 1 }}
                                    </th>
                                    <td>{{$ticket->title}}</td>
                                    <td>{{$ticket->category->name}}</td>
                                    <td>{{$ticket->priority->name}}</td>
                                    <td>{{$ticket->status->name}}</td>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isWorker())
                                    <td>{{$ticket->owner->name}}</td>
                                    @endif
                                    <td>{{$ticket->deadline}}</td>
                                    @if(auth()->user()->isAdmin())
                                    <td>{{($ticket->worker) ? $ticket->worker->name : "Not assigned" }}</td>
                                    @endif
                                    <td class="nj-button-row-table-small">
                                        <a href="{{route('tickets.show', $ticket->id)}}" class="nj-button-primary">Show
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
        @isset($quote)
            <div class="col-md-8 mt-4 mb-3">
                <div class="card text-center">
                    <div class="card-header">Random Quote</div>
                    <div class="card-body">
                        "{{$quote->quote}}" ~ {{$quote->author}}
                    </div>
                </div>
            </div>
        @endisset
    </div>
</div>
@endsection
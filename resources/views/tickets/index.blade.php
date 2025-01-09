@extends('layouts.app')

@section('content')
<div class="nj-container-list">
    <div class="card">
        <div class="card-header">All tickets</div>
        <div class="card-body">
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
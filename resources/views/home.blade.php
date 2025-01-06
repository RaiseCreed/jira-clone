@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Wszystkie tickety') }}</div>

                <div>
                  <a href="{{ route('tickets.create') }}" class="btn btn-primary mt-3">Dodaj</a>
                </div>
                

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
                            <th scope="col">Nazwa</th>
                            <th scope="col">Kategoria</th>
                            <th scope="col">Priorytet</th>
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
                                <a href="{{route('tickets.show', $ticket->id)}}" class="btn btn-primary">Szczegóły</a> 
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
</div>
@endsection

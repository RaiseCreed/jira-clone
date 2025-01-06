@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Szczegóły ticketu</h1>
    <div class="card">
        <div class="card-body">
            Nazwa: {{$ticket->title}} <br>

            Kategoria: {{$ticket->category->name}} <br>

            Priorytet: {{$ticket->priority->name}} <br>

            Status: {{$ticket->status->name}} <br>

            Deadline: {{$ticket->deadline}} <br>

            Opis: {{$ticket->content}} <br>

            Utwrzono: {{$ticket->created_at}} <br>

            <p><a href="{{ route('tickets.edit', $ticket->id) }}">Edit</a></p>
            <p><a href="{{ route('home') }}">Powrót</a></p>

            <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Usuń</button>
            </form>

        </div>
    </div>
</div>
@endsection
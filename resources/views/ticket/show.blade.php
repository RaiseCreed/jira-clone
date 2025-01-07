<!-- resources/views/tickets/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $ticket->title }}</h1>
        <p>{{ $ticket->description }}</p>
        <p>Status: {{ ucfirst($ticket->status) }}</p>

        @if ($ticket->attachment)
            <div class="mt-3">
                <strong>Załącznik:</strong>
                <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="btn btn-info">
                    Pobierz plik
                </a>
            </div>
        @else
            <p>Brak załącznika.</p>
        @endif
    </div>
@endsection

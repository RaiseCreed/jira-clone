@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edytuj Ticket: {{ $ticket->title }}</h1>

    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Tytuł</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $ticket->title) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Opis</label>
            <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $ticket->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="attachment">Załącznik</label>
            <input type="file" class="form-control-file" id="attachment" name="attachment">
        </div>

        @if(auth()->user()->is_admin) 
            <div class="form-group">
                <label for="assigned_to">Odpowiedzialny</label>
                <select class="form-control" id="assigned_to" name="assigned_to">
                    <option value="">Wybierz użytkownika</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" 
                            {{ old('assigned_to', $ticket->assigned_to) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Zaktualizuj Ticket</button>
    </form>

    <hr>

    <h3>Załączniki:</h3>
    <ul>
        @foreach($ticket->attachments as $attachment)
            <li>
                <a href="{{ asset('storage/' . $attachment->file_path) }}" download>
                    {{ $attachment->file_name }}
                </a>
        
                <form action="{{ route('tickets.destroyAttachment', [$ticket->id, $attachment->id]) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>
@endsection

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

            <p><a href="{{ route('home') }}">Go back</a></p>

            @if(Auth::user()->id == $ticket->owner_id)

                <p><a href="{{ route('tickets.edit', $ticket->id) }}">Edit</a></p>
                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>

            @elseif(Auth::user()->isWorker() && Auth::user()->id == $ticket->worker_id)

                {{-- Widget zmiany fazy zlecenia --}}
                <p>Change phase</p>

                {{-- Dodawanie komentarza --}}
                <form action="{{route("tickets.add-comment")}}" method="POST">
                    @csrf
                    <input type="text" name="comment" id="comment" placeholder="Comment">
                    <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                    <button type="submit" class="btn btn-danger">Add comment</button>
                </form>
            @endif

            <p>Comments:</p>
            {{-- Listowanie komentarzy --}}
            @foreach($comments as $comment)
            <div class="card">
                <div class="card-body">
                    <p>{{$comment->author->name}}:   {{$comment->comment}}    {{$comment->created_at}}

                        {{-- // Button do usuwania komentarza --}}
                        @if(Auth::user()->id == $comment->author_id)
                            <form action="{{ route('tickets.delete-comment', $comment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        @endif
                    </p>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>
@endsection
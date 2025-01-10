@extends('layouts.app')

@section('content')
<div class="nj-container">
    <div class="card">
        <div class="card-header">Ticket details</div>
        <div class="card-body row">

            {{-- Przypisanie workera do wykonania ticketa --}}
            @if(Auth::user()->isAdmin() && !$ticket->worker)
            <div class="col-12 col-sm-4 order-1 order-sm-2">
                <h6 style="color: red">This ticket has no assigned staff member! Select who should handle this ticket:
                </h6>
                <form action="{{ route('tickets.assignWorker', $ticket->id) }}" method="POST">
                    @csrf
                    <select name="selectedWorker" class="form-select">
                        @foreach(App\Models\User::where('role','worker')->get() as $worker)
                        <option value="{{$worker->id}}">{{$worker->name}}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="mt-3 mb-3 w-100 float-end nj-button-danger">Assign</button>
                </form>
            </div>
            @endif
            <div class="col-12 col-sm-8 nj-labeled-text-form order-2 order-sm-1">
                <div class="nj-labeled-text-horizontal">
                    <label for="name">Name</label>
                    <p id="name">{{$ticket->title}}</p>
                </div>
                <div class="nj-labeled-text-horizontal">
                    <label for="category">Category</label>
                    <p id="category">{{$ticket->category->name}}</p>
                </div>
                <div class="nj-labeled-text-horizontal">
                    <label for="priority">Priority</label>
                    <p id="priority">{{$ticket->priority->name}}</p>
                </div>
                <div class="nj-labeled-text-horizontal">
                    <label for="category">Category</label>
                    <p id="category">{{$ticket->category->name}}</p>
                </div>
                <div class="nj-labeled-text-horizontal">
                    <label for="status">Status</label>
                    <p id="status">{{$ticket->status->name}}</p>
                </div>
                <div class="nj-labeled-text-horizontal">
                    <label for="created">Created</label>
                    <p id="created">{{$ticket->created_at}}</p>
                </div>
                <div class="nj-labeled-text-horizontal">
                    <label for="deadline">Deadline</label>
                    <p id="deadline">{{$ticket->deadline}}</p>
                </div>
                <div class="nj-labeled-text-horizontal">
                    <label for="description">Description</label>
                    <p id="description">{{$ticket->content}}</p>
                </div>
                @if(Auth::user()->isAdmin() && $ticket->worker)
                <div class="nj-labeled-text-horizontal">
                    <label for="assigned">Assigned</label>
                    <p id="assigned">{{$ticket->worker->name}}</p>
                </div>
                @endif
            </div>
            <div class="order-3">
                <hr />
                <div class="nj-button-row">
                    <a type="button" class="nj-button-secondary" href="{{ route('home') }}">Go back</a>

                    <div class="btn-toolbar gap-3 col-sm-auto col-12">
                        @if(Auth::user()->id == $ticket->owner_id)

                        <a type="button" class="nj-button-primary"
                            href="{{ route('tickets.edit', $ticket->id) }}">Edit</a>
                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="nj-button-danger">Delete</button>
                        </form>

                        @elseif(Auth::user()->isWorker() && Auth::user()->id == $ticket->worker_id)

                        {{-- Widget zmiany fazy zlecenia --}}
                        <a type="button" class="nj-button-primary" href="">Change phase</a>
                        @endif
                    </div>
                </div>
                <hr />
                {{-- Dodawanie komentarza --}}
                <form {{-- action="{{route(" tickets.add-comment")}}" --}} method="POST">
                    @csrf
                    <textarea class="col-12 form-control" type="text" name="comment" id="comment"
                        placeholder="Comment"></textarea>
                    <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                    <div class="mt-3 nj-button-row justify-content-end">
                        <button type="submit" class="nj-button-primary">Add comment</button>
                    </div>
                </form>
                <p class="mt-3">Comments:</p>
                {{-- Listowanie komentarzy --}}
                @foreach($comments as $comment)
                <div class="card">
                    <div class="card-body">
                        <p>{{$comment->author->name}}: {{$comment->comment}} {{$comment->created_at}}

                            {{-- // Button do usuwania komentarza --}}
                            @if(Auth::user()->id == $comment->author_id)
                        <form action="{{ route('tickets.delete-comment', $comment->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this comment?');">
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
</div>
@endsection
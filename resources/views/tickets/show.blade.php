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

                @if((Auth::user()->isAdmin() || Auth::user()->isWorker()) && $ticket->notes)
                    <div class="nj-labeled-text-horizontal">
                        <label for="notes">Work notes</label>
                    </div>
                    <textarea class="form-control" id="" cols="60" rows="10" disabled>{{$ticket->notes}}</textarea>
                @endif

            </div>
            <div class="order-3">
                <hr />
                <div class="nj-button-row">
                    <a type="button" class="nj-button-secondary" href="{{ route('home') }}">Go back</a>

                    <div class="btn-toolbar gap-3 col-sm-auto col-12">
                        @if(Auth::user()->id == $ticket->owner_id && $ticket->status->id == 1)
                        {{-- Widget edycji i usuwania zlecenia --}}

                        <a type="button" class="nj-button-primary"
                            href="{{ route('tickets.edit', $ticket->id) }}">Edit</a>
                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="nj-button-danger">Delete</button>
                        </form>
                        @endif
                    </div>
                </div>
                
                <hr />
                @if(Auth::user()->isWorker() && Auth::user()->id == $ticket->worker_id)
                    <div class="nj-button-row mt-4 mb-4" style="justify-content: center; align-items: center;">
                        {{-- Widget zmiany fazy zlecenia --}}
                        Change phase:
                        <form action="{{route('tickets.changePhase',$ticket->id)}}" method="POST">
                            @csrf
                            <input type="submit" class="{{($ticket->status->name=='Analysis') ? 'nj-button-secondary' : 'nj-button-primary'}}" value="Analysis" name="2">
                            <input type="submit" class="{{($ticket->status->name=='Ongoing') ? 'nj-button-secondary' : 'nj-button-primary'}}" value="Ongoing" name="3">
                            <input type="submit" class="{{($ticket->status->name=='Testing') ? 'nj-button-secondary' : 'nj-button-primary'}}" value="Testing" name="4">
                            <input type="submit" class="{{($ticket->status->name=='Ready') ? 'nj-button-secondary' : 'nj-button-primary'}}" value="Ready" name="5">
                            <input type="submit" class="{{($ticket->status->name=='Closed') ? 'nj-button-secondary' : 'nj-button-primary'}}" value="Closed" name="6">
                            <input type="submit" class="{{($ticket->status->name=='Rejected') ? 'nj-button-secondary' : 'nj-button-primary'}}" value="Rejected" name="7">
                        </form>
                    </div>
                    <hr />
                @endif
                
                {{-- Dodawanie komentarza --}}
                <form action="{{route("tickets.add-comment")}}" method="POST">
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
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-7">
                                <b>{{$comment->author->name}}</b> <br>
                                <span style="font-size: 11px">{{$comment->created_at}}</span>
                                <p class="mt-3">{{$comment->comment}}</p>
                            </div>

                            <div class="col-5 text-center" style="justify-content: center;display: flex;align-items: center;">
                                {{-- // Button do usuwania komentarza --}}
                                @if(Auth::user()->id == $comment->author_id)
                                    <form action="{{ route('tickets.delete-comment', $comment->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>   
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
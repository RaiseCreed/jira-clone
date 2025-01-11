@extends('layouts.app')

@section('content')
    <div class="nj-container">
        <div class="card">
            <div class="card-header">Edit Ticket</div>
            <div class="card-body">
                <form class="row" action="{{ route('tickets.update', ['ticket' => $ticket->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control"
                            value="{{ old('title', $ticket->title) }}" required>
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" id="content" class="form-control" rows="5" required>{{ old('content', $ticket->content) }}</textarea>
                        @error('content')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-3 col-sm-6">
                        <label for="ticket_category_id" class="form-label">Category</label>
                        <select name="ticket_category_id" id="ticket_category_id" class="form-select" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('ticket_category_id', $ticket->ticket_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('ticket_category_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div class="mb-3 col-sm-6">
                        <label for="ticket_priority_id" class="form-label">Priority</label>
                        <select name="ticket_priority_id" id="ticket_priority_id" class="form-select" required>
                            @foreach ($priorities as $priority)
                                <option value="{{ $priority->id }}"
                                    {{ old('ticket_priority_id', $ticket->ticket_priority_id) == $priority->id ? 'selected' : '' }}>
                                    {{ $priority->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('ticket_priority_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    {{-- <div class="mb-3">
                    <label for="ticket_status_id" class="form-label">Status</label>
                    <select name="ticket_status_id" id="ticket_status_id" class="form-select" required>
                        @foreach ($statuses as $status)
                        <option value="{{ $status->id }}" {{ old('ticket_status_id', $ticket->ticket_status_id) ==
                            $status->id ?
                            'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('ticket_status_id')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}

                    <!-- Deadline -->
                    <div class="mb-3 col-sm-6">
                        <label for="deadline" class="form-label">Deadline</label>
                        <input type="datetime-local" name="deadline" id="deadline" class="form-control"
                            value="{{ old('deadline', $ticket->deadline->format('Y-m-d\TH:i')) }}" required>
                        @error('deadline')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="nj-button-row">
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="nj-button-secondary">Go back</a>
                        <button type="submit" class="nj-button-primary">Update Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

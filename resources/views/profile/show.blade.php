@extends('layouts.app')

@section('content')
<div class="container">
    <h1>User Profile</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $user->name }}</h5>
            <p class="card-text">
                <strong>Email:</strong> {{ $user->email }}
            </p>
            <p class="card-text">
                <strong>Created at:</strong> {{ $user->created_at->format('F d, Y') }}
            </p>

            <p><a href="{{ route('profile.edit') }}">Edit</a></p>
        </div>
    </div>
</div>
@endsection
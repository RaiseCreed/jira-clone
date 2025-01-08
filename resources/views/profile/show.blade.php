@extends('layouts.app')

@section('content')
<div class="nj-container">
    <div class="card">
        <div class="card-header">User Profile</div>
        <div class="card-body">
            <h5 class="card-title">{{ $user->name }}</h5>
            <p class="card-text">
                <strong>Email:</strong> {{ $user->email }}
            </p>
            <p class="card-text">
                <strong>Created at:</strong> {{ $user->created_at->format('F d, Y') }}
            </p>
            <a type="button" href="{{ route('profile.edit') }}" class="nj-button-primary mt-3 float-end">Edit</a>
        </div>
    </div>
</div>
@endsection
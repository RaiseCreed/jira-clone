@extends('layouts.app')

@section('content')
<div class="nj-container">
    <div class="card">
        <div class="card-header">User Profile</div>
        <div class="card-body">
            <div class="nj-labeled-text-form">
                <div class="nj-labeled-text-horizontal">
                    <label for="name">Name</label>
                    <p id="name">{{ $user->name }}</p>
                </div>
                <div class="nj-labeled-text-horizontal">
                    <label for="email">Email</label>
                    <p id="email">{{ $user->email }}</p>
                </div>
                <div class="nj-labeled-text-horizontal">
                    <label for="created_at">Created at</label>
                    <p id="created_at">{{ $user->created_at->format('F d, Y') }}</p>
                </div>
            </div>
            <a type="button" href="{{ route('profile.edit') }}" class="nj-button-primary mt-3 float-end">Edit</a>
        </div>
    </div>
</div>
@endsection
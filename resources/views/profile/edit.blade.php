@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Profile</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Formularz edycji -->
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf

        {{-- Nazwa usera --}}
        <div class="form-group">
            <label for="name">Name</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                class="form-control @error('name') is-invalid @enderror" 
                value="{{ old('name', $user->name) }}" 
                required>
            @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Adres mailowy --}}
        <div class="form-group">
            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-control @error('email') is-invalid @enderror" 
                value="{{ old('email', $user->email) }}" 
                required>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
        
    </form>
</div>
@endsection
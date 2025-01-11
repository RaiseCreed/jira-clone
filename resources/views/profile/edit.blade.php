@extends('layouts.app')

@section('content')
<div class="nj-container">
    <div class="card">
        <div class="card-header">Edit Profile</div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Formularz edycji -->
            <form class="row" action="{{ route('profile.update') }}" method="POST">
                @csrf

                {{-- Nazwa usera --}}
                <div class="form-group mb-3">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}" required>
                    @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Adres mailowy --}}
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}" required>
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="nj-button-row">
                    <a type="button" href="{{ route('profile.show') }}" class="nj-button-secondary">
                        Back</a>
                    <button type="submit" class="nj-button-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
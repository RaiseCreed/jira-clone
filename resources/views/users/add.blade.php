@extends('layouts.app')

@section('content')
<div class="nj-container">
    <div class="card">
        <div class="card-header">{{ __('Add user') }}</div>
        <div class="card-body">
            <form class="row" method="POST" action="{{ route('users.add.post') }}">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">Name</label>
                    <input id="name" type="text" class="form-control" name="name" required autocomplete="name"
                        autofocus>
                    @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required
                        autocomplete="email">
                    @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3 col-sm-6">
                    <label for="role">Role</label>
                    <select name="role" id="role" class="form-control">
                        <option value="admin">Admin</option>
                        <option value="customer">Customer</option>
                        <option value="worker">Worker</option>
                    </select>
                    @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="nj-button-row">
                    <button type="submit" class="nj-button-primary">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
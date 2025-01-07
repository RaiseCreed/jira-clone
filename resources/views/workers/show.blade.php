@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Workers list</h1>
    <div class="card">
        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created at</th>
                        <th>Password reset</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workers as $item)
                    <tr>
                        <td>{{ $item -> name}}</td>
                        <td>{{ $item -> email}}</td>
                        <td>{{ $item -> created_at}}</td>
                        <td>
                            <a href="{{ route('password.email', ['email' => $item->email]) }}" class="btn btn-success">Reset</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
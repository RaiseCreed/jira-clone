@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Customers list</h1>
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
                        <th>Delete user</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $item)
                    <tr>
                        <td>{{ $item -> name}}</td>
                        <td>{{ $item -> email}}</td>
                        <td>{{ $item -> created_at}}</td>
                        <td>
                            <a href="{{ route('password.email', ['email' => $item->email]) }}" class="btn btn-success">Reset</a>
                        </td>
                        <td>
                        <form action="{{ route('users.delete', ['email' => $item->email]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
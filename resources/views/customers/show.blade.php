@extends('layouts.app')

@section('content')
<div class="nj-container-list">
    <div class="card col-lg-12">
        <div class="card-header">Customers list</div>
        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach($customers as $item)
                        <tr class="align-middle">
                            <td>{{ $item -> name}}</td>
                            <td>{{ $item -> email}}</td>
                            <td>{{ $item -> created_at}}</td>
                            <td class="nj-button-row-table">
                                <a href="{{ route('password.email', ['email' => $item->email]) }}"
                                    class="nj-button-primary">Password Reset</a>
                                <form action="{{ route('users.delete', ['email' => $item->email]) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="nj-button-red">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
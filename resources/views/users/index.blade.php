<!-- resources/views/users/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Users List</h2>
                    <a class="btn btn-success" href="{{ route('users.create') }}">Create New User</a>
                </div>
            </div>
        </div>

        <!-- Search Form -->
        <div class="row mb-3">
            <div class="col-lg-12">
                <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search users..."
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">Search</button>
                        @if (request('search'))
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Clear</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    <a class="btn btn-primary btn-sm" href="{{ route('users.edit', $user->id) }}">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="d-flex justify-content-center">
                {!! $users->appends(request()->query())->render() !!}
            </div>
        @endif
    </div>
@endsection

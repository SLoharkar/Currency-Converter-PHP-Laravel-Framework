@extends('dashboard')

@section('title', 'User Management')

@section('content')
    <h2 class="text-center mb-4">User Management</h2>
    
    <!-- Table displaying user data -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Username</th>
                <th>Password</th>
                <th>Roles</th>
                <th colspan="2" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Iterate over users passed from the controller -->
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->username }}</td>
                <td>{{ $user->plain_password }}</td>
                <td>{{ implode(', ', $user->roles) }}</td>
                <td class="text-center">
                    <form action="{{ route('admin.user_update_form') }}" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </td>
                <td class="text-center">
                    <form action="{{ route('admin.user_delete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection

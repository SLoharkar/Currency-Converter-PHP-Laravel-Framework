@extends('dashboard')

@section('title', 'Update User')

@section('content')

    <div class="container my-4">
        <h2 class="text-center mb-4">Update User</h2>
        
        <form method="post" action="{{ route('admin.user_update') }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
                @error('username')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="{{ $user->plain_password }}" placeholder="Leave blank to keep the current password">
                @error('password')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="roles">Roles</label>
                <select class="form-control" id="roles" name="roles[]" multiple>
                    <option value="ROLE_USER" {{ in_array('ROLE_USER', $user->roles) ? 'selected' : '' }}>ROLE_USER</option>
                    <option value="ROLE_ADMIN" {{ in_array('ROLE_ADMIN', $user->roles) ? 'selected' : '' }}>ROLE_ADMIN</option>
                </select>
            </div>

            <input type="hidden" name="id" value="{{ session('id') }}">

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.user_management_form') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection

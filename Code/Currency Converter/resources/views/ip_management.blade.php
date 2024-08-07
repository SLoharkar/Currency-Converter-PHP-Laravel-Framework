@extends('dashboard')

@section('title', 'IP Address Management')

@section('content')

@if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
@endif

<div class="container mt-5">
    <h2>IP Address Management</h2>

    <!-- Form for adding a new IP address -->
    <form action="{{route('ip.management_add')}}" method="POST" class="form-inline mb-3">
        @csrf
        @method('POST')
        <div class="form-group mx-sm-3 mb-2">
            <input type="text" name="ip_address" class="form-control mr-2" placeholder="Enter IP Address" value="192.168.1.1" required>
        </div>
        <button type="submit" class="btn btn-primary mb-2">Add</button>
    </form>

    <!-- Table to display the list of IP addresses -->
    <table class="table table-bordered" style="width: 40%;">
        <thead>
            <tr>
                <th class="text-center">IP Address</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($ipAddresses as $ipAddress)
                <tr>
                    <td>
                        <!-- Form for updating the IP address -->
                        <form action="{{route('ip.management_update')}}" method="POST" class="d-flex align-items-center">
                            @csrf
                            @method('PUT')
                            <input type="text" name="ip_address" class="form-control mr-2" value="{{ $ipAddress->ip_address }}">
                            <input type="hidden" name="id" value="{{ $ipAddress->id }}">
                            <button type="submit" class="btn btn-success">Update</button>
                        </form>
                    </td>
                    <td>
                        <!-- Form for deleting the IP address -->
                        <form action="{{route('ip.management_del')}}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $ipAddress->id }}">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@extends('dashboard')

@section('title', 'Currencies Export')

@section('content')
    <div class="container my-4">
        <h2>Currencies Export</h2>

        <div class="row">
            <!-- Export to Excel -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Export to Excel</h5>
                        <form action="{{ route('currencies.export_excel') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="excelFileName">File Name (Optional)</label>
                                <input type="text" class="form-control" id="excelFileName" name="excel_file_name" placeholder="Enter file name (leave empty for auto-generated)">
                                @error('excel_file_name')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-primary custom-btn" name="export_excel">Export to Excel</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Export to Database -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Export to Database</h5>
                        <form action="{{ route('currencies.export_database') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="dbHost">Database Host</label>
                                <input type="text" class="form-control" id="dbHost" name="db_host" placeholder="e.g., localhost" required>
                                @error('db_host')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label for="dbPort">Database Port</label>
                                <input type="number" class="form-control" id="dbPort" name="db_port" placeholder="e.g., 3306" required>
                                @error('db_port')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label for="dbName">Database Name</label>
                                <input type="text" class="form-control" id="dbName" name="db_name" placeholder="e.g., my_database" required>
                                @error('db_name')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label for="dbTableName">Table Name (Optional)</label>
                                <input type="text" class="form-control" id="dbTableName" name="db_table_name" placeholder="Enter table name (leave empty for default)">
                                @error('db_table_name')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label for="dbUsername">Username</label>
                                <input type="text" class="form-control" id="dbUsername" name="db_username" placeholder="e.g., root" required>
                                @error('db_username')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label for="dbPassword">Password</label>
                                <input type="password" class="form-control" id="dbPassword" name="db_password" placeholder="e.g., password">
                                @error('db_password')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <button type="button" class="btn btn-secondary" id="setDefaults">Set Default Values</button>
                            <button type="submit" class="btn btn-primary custom-btn" name="export_database">Export to Database</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/currencies_export.js') }}"></script>
@endsection
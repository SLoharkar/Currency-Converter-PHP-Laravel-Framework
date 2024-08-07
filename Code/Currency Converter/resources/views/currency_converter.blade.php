@extends('dashboard')

@section('title', 'Currency Converter')

@section('content')
    <h2 class="text-center mb-4">Currency Converter</h2>

                <!-- Data Source Selection Form -->
                @if($role == 'admin')
                <div class="card mx-auto mb-4" style="max-width: 600px;">
                        <div class="card-body">
                            <form id="data-source-form" method="post" action="{{ route('currency.converter_form', $role) }}">
                                @csrf
                                @method('GET')
                                <div class="form-group">
                                    <label for="data_source" class="font-weight-bold">Data Source</label>
                                    <select id="data_source" name="data_source" class="form-control" required>
                                        <option value="default" {{ $data_source == 'default' ? 'selected' : '' }}>Default</option>
                                        <option value="excel" {{ $data_source == 'excel' ? 'selected' : '' }}>Excel</option>
                                        <option value="database" {{ $data_source == 'database' ? 'selected' : '' }}>Database</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Select Data Source</button>
                            </form>
                        </div>
                </div>
                @endif

    @if ($data_source)        
    <!-- Currency Conversion Form -->

        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <form method="post" action="{{ route('currency.converter', $role) }}">
                    @csrf                 

                    <!-- From Currency Selector -->
                    <div class="form-group">
                        <label for="from_currency" class="font-weight-bold text-d">From Currency</label>
                        <select id="from_currency" name="from_currency" class="form-control" required>
                            <option value="" disabled selected>Select a currency</option>
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency['name'] }}">{{ $currency['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- To Currency Selector -->
                    <div class="form-group">
                        <label for="to_currency" class="font-weight-bold text-d">To Currency</label>
                        <select id="to_currency" name="to_currency" class="form-control" required>
                            <option value="" disabled selected>Select a currency or All</option>
                            <option value="all">All Available Currencies</option>
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency['name'] }}">{{ $currency['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Limit Selector -->
                    <div class="form-group" id="limit-group" style="display: none;">
                        <label for="limit" class="font-weight-bold">Number of Available Currencies (<span id="total-currencies">{{ count($currencies)-1 }}</span> available)</label>
                        <select id="limit" name="limit" class="form-control">
                            <!-- Options will be dynamically generated by JavaScript -->
                        </select>
                    </div>

                    <!-- Amount Input -->
                    <div class="form-group">
                        <label for="amount" class="font-weight-bold">Amount</label>
                        <input type="number" id="amount" name="amount" class="form-control" step="0.01" placeholder="Enter the amount" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Convert</button>
                </form>
            </div>
        </div>

        @if (!empty($converted))
            <h2 class="mt-5">Converted Amounts</h2>
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered">
                    <thead class="thead-dark" style="position: -webkit-sticky; position: sticky; top: 0; background-color: #343a40; color: #ffffff; z-index: 1;">
                        <tr>
                            <th>SR No.</th>
                            <th>Currency</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($converted as $index => $conversion)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $conversion['name'] }}</td>
                                <td>{{ $conversion['converted_amount'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('js/currency_converter.js') }}"></script>
@endsection

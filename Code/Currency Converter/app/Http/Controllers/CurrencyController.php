<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\CurrencyService;



class CurrencyController{


    private $currencyService;
    private $defaultDataSource;



    /**
     * CurrencyController constructor.
     *
     * This constructor initializes the CurrencyService and sets the default data source from environment variables.
     * It also logs the initialization process.
     *
     * @param  \App\Services\CurrencyService  $currencyService  The currency service instance.
     * @return void
    */
    public function __construct(CurrencyService $currencyService){

        Log::info('Initializing CurrencyController.');

        $this->currencyService = $currencyService;
        $this->defaultDataSource = env('CURRENCY_DATA_SOURCE');

        Log::info('Default data source set.', ['defaultDataSource' => $this->defaultDataSource]);
    }



    /**
     * Display the currency converter form.
     *
     * This method handles the display of the currency converter form, including loading currency data from a specified
     * or default data source. It logs the process and handles any exceptions that occur during data loading.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request instance.
     * @param  string|null  $role  The role of the user, if applicable.
     * @param  array  $converted  An array of converted currency data.
     * @param  string|null  $dataSource  The data source for currency information.
     * @return View  The currency converter form view.
    */
    public function currencyConverterForm(Request $request, $role, $converted = [], $dataSource = null){

        Log::info('Displaying currency converter form.', [
            'role' => $role,
            'converted' => $converted,
            'data_source' => $dataSource
        ]);
        
        $currencies = [];

        // Determine the data source to use
        $dataSource = $dataSource ?? $request->input('data_source', $this->defaultDataSource);
        session(['data_source' => $dataSource]);

        Log::info('Using data source.', ['data_source' => $dataSource]);

        try {

            // Load currency data
            $currencies = $this->currencyService->loadCurrency(false, $dataSource);

            Log::info('Currency data loaded successfully.', ['data_source' => $dataSource]);
        } catch (\Exception $e){

            Log::error('Failed to load currency data.', ['error' => $e->getMessage()]);

            session()->flash('error', 'Failed to load currency data: ' . $e->getMessage());
        }

        // Return the view with the required data
        return view('currency_converter')->with([
            'converted' => $converted,
            'currencies' => $currencies,
            'data_source' => $dataSource
        ]);
    }



    /**
     * Convert currency based on the user's input.
     *
     * This method handles the conversion of currency based on user input, including the source and target currencies,
     * the amount to convert, and an optional limit. It logs the process and handles any exceptions that occur during conversion.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request instance.
     * @return View  The currency converter form view with the conversion results.
    */
    public function currencyConverter(Request $request){

        Log::info('Starting currency conversion.', [
            'from_currency' => $request->input('from_currency'),
            'to_currency' => $request->input('to_currency'),
            'amount' => $request->input('amount')
        ]);

        $converted = [];

        // Retrieve the data source from the session
        $dataSource = session('data_source');

        Log::info('Using data source for conversion.', ['data_source' => $dataSource]);
        
        // Retrieve the 'from_currency', 'to_currency', and 'amount' from the request
        $from = $request->input('from_currency');
        $toCurrency = $request->input('to_currency');
        $amount = (float) $request->input('amount');
        $limit = $request->input('limit', 10); // Default to 10 if not provided

        try {

            // Perform the currency conversion
            $converted = $this->currencyService->convertCurrency($from, $toCurrency, $amount, $limit, $dataSource);

            Log::info('Currency conversion successful.', [
                'from_currency' => $from,
                'to_currency' => $toCurrency,
                'amount' => $amount,
                'converted' => $converted
            ]);
        } catch(\Exception $e){

            Log::error('Currency conversion failed.', ['error' => $e->getMessage()]);

            session()->flash('error', $e->getMessage());
        }

        // Return the currency converter form view with the conversion results
        return $this->currencyConverterForm($request, $role=null, $converted, $dataSource);
    }

}

?>
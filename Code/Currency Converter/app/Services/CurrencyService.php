<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;



class CurrencyService{


    private $excelStoragePath;
    private $defaultTablename;



    /**
     * CurrencyService constructor.
     *
     * This constructor initializes the CurrencyService with configuration values from environment variables.
     * It also logs the initialization process.
     *
     * @return void
    */
    public function __construct(){

        Log::info('Initializing CurrencyService.');

        $this->excelStoragePath = env('EXCEL_STORAGE_PATH');
        $this->defaultTablename = env('DEFAULT_TABLE_NAME');

        Log::info('CurrencyService configuration set.', [
            'excelStoragePath' => $this->excelStoragePath,
            'defaultTablename' => $this->defaultTablename
        ]);
    }



    /**
     * Convert currency from one type to another.
     *
     * This method handles the conversion of currency from a specified source currency to a target currency or all available currencies.
     * It ensures the source currency exists and the amount is positive before performing the conversion.
     * It logs the process and any errors that occur.
     *
     * @param  string  $from  The source currency code.
     * @param  string  $toCurrency  The target currency code or 'all' to convert to all currencies.
     * @param  float  $amount  The amount to convert.
     * @param  int  $limit  The limit for conversion results when converting to all currencies.
     * @param  string  $dataSource  The data source for currency rates.
     * @return array  The converted currency values.
    */
    public function convertCurrency($from, $toCurrency, $amount, $limit, $dataSource){

        Log::info('Starting currency conversion.', [
            'from_currency' => $from,
            'to_currency' => $toCurrency,
            'amount' => $amount,
            'limit' => $limit,
            'data_source' => $dataSource
        ]);
        
        // Ensure the from currency exists and the amount is positive
        if (!empty($from) && $amount > 0 ){

            // Fetch available currencies and their rates
            $currencies = $this->loadCurrency(true, $dataSource);

            Log::info('Currencies loaded successfully.', ['data_source' => $dataSource]);

            if ($toCurrency === 'all'){

                // Convert to all available currencies
                $result = $this->convertToAllCurrencies($currencies, $from, $amount, $limit);
                
                Log::info('Converted to all currencies.', [
                    'from_currency' => $from,
                    'amount' => $amount,
                    'limit' => $limit,
                    'result' => $result
                ]);

                return $result;
            } else{

                // Convert to a specific currency
                $result = $this->convertToSpecificCurrency($currencies, $from, $toCurrency, $amount);

                Log::info('Converted to specific currency.', [
                    'from_currency' => $from,
                    'to_currency' => $toCurrency,
                    'amount' => $amount,
                    'result' => $result
                ]);

                return $result;
            }

        }else{
            Log::error('Invalid input for currency conversion.', [
                'from_currency' => $from,
                'amount' => $amount
            ]);
        }
    }



    /**
     * Convert an amount to all available currencies up to a specified limit.
     *
     * This method handles the conversion of an amount from a source currency to all available target currencies
     * up to the specified limit. It logs the process and any errors that occur.
     *
     * @param  array  $currencies  An array of currencies and their rates.
     * @param  string  $from  The source currency code.
     * @param  float  $amount  The amount to convert.
     * @param  int  $limit  The limit for conversion results.
     * @return array  The converted currency values.
    */
    private function convertToAllCurrencies(array $currencies, string $from, float $amount, int $limit): array{
        
        Log::info('Starting conversion to all currencies.', [
            'from_currency' => $from,
            'amount' => $amount,
            'limit' => $limit
        ]);
    
        
        $limitedCurrencies = array_slice($currencies, 0, $limit + 1);
        $converted = [];

        foreach ($limitedCurrencies as $currencyData){

            // Skip the source currency
            if ($currencyData['name'] === $from){
                continue;
            }

            // Calculate the converted amount
            $converted[] = [
                'name' => $currencyData['name'],
                'converted_amount' => $amount * $currencyData['rate']
            ];
        }

        Log::info('Conversion to all currencies completed.', [
            'from_currency' => $from,
            'amount' => $amount,
            'limit' => $limit,
            'converted' => $converted
        ]);

        return $converted;
    }



    /**
     * Convert an amount from a source currency to a specific target currency.
     *
     * This method handles the conversion of an amount from a source currency to a specified target currency.
     * It logs the process and any errors that occur.
     *
     * @param  array  $currencies  An array of currencies and their rates.
     * @param  string  $from  The source currency code.
     * @param  string  $toCurrency  The target currency code.
     * @param  float  $amount  The amount to convert.
     * @return array  The converted currency values.
    */
    private function convertToSpecificCurrency(array $currencies, string $from, string $toCurrency, float $amount): array{

        Log::info('Starting conversion to specific currency.', [
            'from_currency' => $from,
            'to_currency' => $toCurrency,
            'amount' => $amount
        ]);

        // Find the source and target currencies in the provided currencies array
        $fromCurrency = $this->findCurrency($currencies, $from);
        $toCurrencyData = $this->findCurrency($currencies, $toCurrency);

        if ($fromCurrency && $toCurrencyData){
            
            // Calculate the converted amount
            $convertedAmount = $amount * $toCurrencyData['rate'] / $fromCurrency['rate'];
            
            Log::info('Conversion to specific currency completed.', [
                'from_currency' => $from,
                'to_currency' => $toCurrency,
                'amount' => $amount,
                'converted_amount' => $convertedAmount
            ]);

            return [[
                'name' => $toCurrencyData['name'],
                'converted_amount' => $convertedAmount
            ]];
        }

        Log::error('Currency conversion failed. Source or target currency not found.', [
            'from_currency' => $from,
            'to_currency' => $toCurrency
        ]);

        return [];
    }



    /**
     * Find a currency by its name in the given array of currencies.
     *
     * This method searches for a currency by its name in the provided currencies array.
     * It logs the search process and any errors if the currency is not found.
     *
     * @param  array  $currencies  An array of currencies and their rates.
     * @param  string  $currencyName  The name of the currency to find.
     * @return array|null  The currency data if found, or null if not found.
    */
    private function findCurrency(array $currencies, string $currencyName): ?array{

        Log::info('Searching for currency.', ['currency_name' => $currencyName]);

        foreach ($currencies as $currency){

            if ($currency['name'] === $currencyName){

                Log::info('Currency found.', ['currency_name' => $currencyName]);

                return $currency;
            }
        }

        Log::error('Currency not found.', ['currency_name' => $currencyName]);

        return null;
    }



    /**
     * Load currency data from a specified data source.
     *
     * This method loads currency data from the specified data source (Excel, database, or default).
     * It includes the option to include the currency rates in the result.
     * It logs the process and any errors that occur.
     *
     * @param  bool  $includeRate  Whether to include the currency rates in the result.
     * @param  string  $dataSource  The data source to load currency data from.
     * @return array  The loaded currency data.
    */
    public function loadCurrency($includeRate, $dataSource){

        Log::info('Loading currency data.', [
            'include_rate' => $includeRate,
            'data_source' => $dataSource
        ]);

        // Load currency data based on the specified data source
        switch($dataSource){

            case 'excel':
                $result = $this->fetchCurrencyDataFromExcel();
                Log::info('Currency data loaded from Excel.');
            break;

            case 'database':
                $result = $this->fetchCurrencyDataFromDatabase();
                Log::info('Currency data loaded from database.');
            break;

            case 'default':
                $result = $this->fetchCurrencyData();
                Log::info('Currency data loaded from default source.');
            break;
        }

        $currencies = [];

        // Process the loaded currency data
        foreach ($result as $currencyData){

            if ($includeRate){

                $currencies[] = [
                    'name' => $currencyData['name'],
                    'rate' => $currencyData['rate'] ?? null // Use null if 'rate' is not set
                ];
            } else{

                // Only include 'name' in the output
                $currencies[] = [
                    'name' => $currencyData['name']
                ];
            }        
        }

        Log::info('Currency data processed.', [
            'include_rate' => $includeRate,
            'currencies' => $currencies
        ]);

        return $currencies;
    }



    /**
     * Fetch currency data from a default online source.
     *
     * This method fetches currency data from the specified URL and returns it as an array.
     * It logs the process and any errors that occur.
     *
     * @return array  The fetched currency data.
     * @throws \Exception If there is an issue with fetching or decoding the JSON data.
    */
    private function fetchCurrencyData(): array{

        $CURRENCY_URL = "http://www.floatrates.com/daily/usd.json";

        Log::info('Fetching currency data from URL.', ['url' => $CURRENCY_URL]);

        // Fetch the data with a timeout of 120 seconds
        $response = Http::timeout(120)->get($CURRENCY_URL);

        if ($response->failed()){
            Log::error('Failed to fetch the JSON URL.', ['url' => $CURRENCY_URL]);
            throw new \Exception("Failed to fetch the JSON URL: " . $CURRENCY_URL);
        }

        $data = $response->json();
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Failed to decode JSON data.');
            throw new \Exception("Failed to decode JSON data.");
        }

        Log::info('Currency data fetched successfully.', ['url' => $CURRENCY_URL]);

        return $data;
    }



    /**
     * Fetch currency data from an Excel file.
     *
     * This method reads currency data from an Excel file specified by the path stored in the environment variable.
     * It logs the process and any errors that occur.
     *
     * @return array  The fetched currency data.
     * @throws \Exception If there is an issue with reading the Excel file.
    */
    private function fetchCurrencyDataFromExcel(): array{

        Log::info('Fetching currency data from Excel file.', ['file_path' => $this->excelStoragePath]);

        $filePath = storage_path($this->excelStoragePath); 

        // Read data from the Excel file
        $data = Excel::toArray($this, $filePath);
        if (empty($data[0])){
            Log::error('Failed to read currency data from Excel.', ['file_path' => $filePath]);
            throw new \Exception("Failed to read currency data from Excel.");
        }

        $excelData = $data[0];
        
        // Remove the header row
        array_shift($excelData);

        $currencies = [];

        // Process each row to extract currency data
        foreach ($excelData as $row){
            $currencies[] = [
                'name' => $row[0],
                'rate' => (float)$row[1]
            ];
        }

        Log::info('Currency data fetched successfully from Excel.', ['file_path' => $filePath]);

        return $currencies;
    }



    /**
     * Fetch currency data from the database.
     *
     * This method fetches currency data from the specified database table.
     * It logs the process and any errors that occur.
     *
     * @return array  The fetched currency data.
     * @throws \Exception If there is an issue with the database connection or retrieval of data.
    */
    private function fetchCurrencyDataFromDatabase(): array{

        Log::info('Fetching currency data from database.');

        // Establish a database connection
        $dbConnection = DB::connection();

        // Test the database connection
        $this->testDbConnection($dbConnection);

        $tableName = $this->defaultTablename;

        // Retrieve currencies from the database
        $currencies = $this->retrieveCurrenciesFromDatabase($dbConnection, $tableName);

        Log::info('Currency data fetched successfully from database.', ['table_name' => $tableName]);

        return $currencies;

    }


    /**
     * Test the database connection.
     *
     * This method tests the database connection to ensure it is working properly.
     * It logs the process and any errors that occur.
     *
     * @param  \Illuminate\Database\Connection  $dbConnection  The database connection instance.
     * @throws \Exception If there is an issue with the database connection.
    */
    private function testDbConnection($dbConnection){

        try {

            Log::info('Testing database connection.');

            // Perform a simple query to test the connection
            $dbConnection->getPdo();

            Log::info('Database connection test successful.');
        } catch (\Exception $e) {

            Log::error('Database connection test failed.', ['error' => $e->getMessage()]);

            throw new \Exception('Could not connect to the database. Please check your configuration.'.$e->getMessage());
        }
    }



    /**
     * Retrieve currencies from the database.
     *
     * This method fetches all currencies from the specified table and converts the result to an array.
     * It logs the process and any errors that occur.
     *
     * @param  \Illuminate\Database\Connection  $dbConnection  The database connection instance.
     * @param  string  $tableName  The name of the table to retrieve data from.
     * @return array  The retrieved currency data.
     * @throws \Exception If there is an issue with retrieving the data from the database.
    */
    private function retrieveCurrenciesFromDatabase($dbConnection, $tableName){

        try{
            Log::info('Retrieving currency data from database.', ['table_name' => $tableName]);

            // Fetch all currencies from the specified table
            $currencies = $dbConnection->table($tableName)->get(['name', 'rate']);

            // Convert the result to an array
            $currencyArray = $currencies->map(function ($currency) {
                return [
                    'name' => $currency->name,
                    'rate' => $currency->rate,
                ];
            })->toArray();

            Log::info('Currency data retrieved successfully from database.', ['table_name' => $tableName]);

            return $currencyArray;
        }catch (\Exception $e){

            Log::error('Failed to retrieve currency data from database.', ['table_name' => $tableName, 'error' => $e->getMessage()]);
            
            throw new \Exception('Failed to retrieve currency data from database: ' . $e->getMessage());    
        }
    }

}

?>
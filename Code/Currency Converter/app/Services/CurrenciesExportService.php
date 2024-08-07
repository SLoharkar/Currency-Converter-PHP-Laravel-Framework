<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;




class CurrenciesExportService implements FromArray, WithHeadings{



    /**
     * Export currencies data to an Excel file.
     *
     * @param string $fileName
     * @return void
    */
    public function exportToExcel($fileName){

        Log::info('Starting export to Excel', ['fileName' => $fileName]);

        if(empty($fileName)){
            $currentDateTime = now()->format('d-M-y_h_i_a'); // Example: 01-Aug-24_01_08_pm
            $fileName = 'currencies_' . $currentDateTime . '.xlsx';
            Log::info('Generated file name', ['fileName' => $fileName]);
        }

        // Ensure the file name ends with .xlsx
        if (!str_ends_with($fileName, '.xlsx')){
            $fileName .= '.xlsx';
            Log::info('Appended .xlsx to file name', ['fileName' => $fileName]);
        }

        try {
            // Use the current instance (which implements FromArray and WithHeadings) to export the data
            Excel::store($this, "excel/$fileName", 'public');
            Log::info('Export to Excel successful', ['fileName' => $fileName]);
        } catch (\Exception $e) {
            Log::error('Export to Excel failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }



    /**
     * Return the array of currencies data.
     *
     * @return array
    */
    public function array(): array{
        return $this->loadCurrencies();        ;
    }



    /**
     * Return the headings for the Excel file.
     *
     * @return array
    */
    public function headings(): array{
        return ['Name', 'Rate'];
    }



    /**
     * Export currencies data to a database.
     *
     * @param array $data
     * @return void
    */
    public function exportToDatabase($data){

        Log::info('Starting export to database', ['data' => $data]);
        
        // Create a database connection using the provided data.
        $dbConnection = $this->createDbConnection($data);

        // Test the database connection to ensure it's valid.
        $this->testDbConnection($dbConnection);

        // Retrieve the table name from the provided data.
        $tableName = $data['table'];

        // Create the table if it doesn't already exist.
        $this->createTableIfNotExists($dbConnection, $tableName);

        // Load the currencies data to be exported.
        $currencies = $this->loadCurrencies();

        // Insert the loaded currencies data into the specified table in the database.
        $this->insertCurrenciesIntoDatabase($dbConnection, $currencies, $tableName);

        Log::info('Export to database successful', ['table' => $tableName]);    
    }



    /**
     * Create a dynamic database connection based on the provided configuration data.
     *
     * @param array $data Array containing database connection details.
     * @return \Illuminate\Database\Connection The database connection instance.
    */
    protected function createDbConnection($data){

        Log::info('Creating dynamic database connection', ['data' => $data]);

        config([
            'database.connections.dynamic' => [
                'driver' => 'mysql',
                'host' => $data['host'],
                'port' => $data['port'],
                'database' => $data['database'],
                'username' => $data['username'],
                'password' => $data['password'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
            ],
        ]);

        Log::info('Database connection configuration set successfully', [
            'host' => $data['host'],
            'database' => $data['database'],
            'table' => $data['table']
        ]);

        // Return a connection instance to the newly configured dynamic database connection.
        return DB::connection('dynamic');
    }



    /**
     * Test the given database connection to ensure it is valid.
     *
     * @param \Illuminate\Database\Connection $dbConnection The database connection instance to test.
     * @return void
     * @throws \Exception If the database connection cannot be established.
    */
    protected function testDbConnection($dbConnection){

        Log::info('Testing database connection');

        try {
            // Attempt to get the PDO instance from the database connection to verify it's working.
            $dbConnection->getPdo();   
            Log::info('Database connection test successful'); 
        } catch (\Exception $e) {
            Log::error('Database connection test failed', ['error' => $e->getMessage()]);
            throw new \Exception('Could not connect to the database. Please check your configuration.'.$e->getMessage());
        }
    }



    /**
     * Create the currencies table if it does not already exist.
     *
     * @param \Illuminate\Database\Connection $dbConnection The database connection instance.
     * @param string $tableName The name of the table to be created.
     * @return void
    */
    protected function createTableIfNotExists($dbConnection, $tableName){

        Log::info('Checking if table exists', ['table' => $tableName]);

        // Check if the table already exists in the database.
        if (!$dbConnection->getSchemaBuilder()->hasTable($tableName)){

            Log::info('Table does not exist, creating table', ['table' => $tableName]);

            // Create the table with the specified columns and properties.
            $dbConnection->getSchemaBuilder()->create($tableName, function ($table) {
                $table->increments('id');
                $table->string('name');
                $table->decimal('rate', 8, 4);
            });
            Log::info('Table created successfully', ['table' => $tableName]);
        } else{
            Log::info('Table already exists', ['table' => $tableName]);
        }
    }



    /**
     * Insert currencies data into the specified database table.
     *
     * @param \Illuminate\Database\Connection $dbConnection The database connection instance.
     * @param array $currencies Array of currencies data to be inserted.
     * @param string $tableName The name of the table where the data will be inserted.
     * @return void
    */
    protected function insertCurrenciesIntoDatabase($dbConnection, $currencies, $tableName){

        Log::info('Starting insertion of currencies into database', ['table' => $tableName, 'currency_count' => count($currencies)]);

        try {

            // Insert each currency into the specified table.
            foreach ($currencies as $currency){
                $dbConnection->table($tableName)->insert($currency);
                Log::info('Inserted currency', ['currency' => $currency]);
            }

            Log::info('All currencies inserted successfully', ['table' => $tableName]);
        } catch (\Exception $e) {
            
            Log::error('Failed to insert currencies into database', ['error' => $e->getMessage()]);

            throw new \Exception('Failed to insert currencies into database: ' . $e->getMessage());
        }
    }

    

    /**
     * Load currencies from an external data source.
     *
     * @return array An array of currencies with their names and rates.
    */
    private function loadCurrencies(){

        Log::info('Loading currencies');

        // Fetch currency data from the external source.
        $result = $this->fetchCurrencyData();

        $currencies = [];

        // Process each currency data item.
        foreach ($result as $currencyData){
            $currencies[] = [
                'name' => $currencyData['name'],
                'rate' => $currencyData['rate'] ?? null // Use null if 'rate' is not set
            ];
            Log::info('Loaded currency', ['name' => $currencyData['name'], 'rate' => $currencyData['rate']]);
        } 

        Log::info('Currencies loaded successfully', ['currency_count' => count($currencies)]);
       
        return $currencies;
    }



    /**
     * Fetch currency data from an external JSON URL.
     *
     * @return array An array of raw currency data.
     * @throws \Exception If fetching or decoding the JSON data fails.
    */
    private function fetchCurrencyData(): array{

        $CURRENCY_URL = "http://www.floatrates.com/daily/usd.json";

        Log::info('Fetching currency data from URL', ['url' => $CURRENCY_URL]);

        $response = Http::timeout(120)->get($CURRENCY_URL);

        if ($response->failed()){
            Log::error('Failed to fetch the JSON URL', ['url' => $CURRENCY_URL, 'status' => $response->status()]);
            throw new \Exception("Failed to fetch the JSON URL: " . $CURRENCY_URL);
        }

        // Decode the JSON response data.
        $data = $response->json();

        if (json_last_error() !== JSON_ERROR_NONE){
            Log::error('Failed to decode JSON data', ['json_error' => json_last_error_msg()]);
            throw new \Exception("Failed to decode JSON data.");
        }

        Log::info('Currency data fetched successfully', ['data_count' => count($data)]);

        return $data;
    }

}

?>

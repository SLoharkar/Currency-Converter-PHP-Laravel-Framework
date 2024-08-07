<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;


use App\Http\Requests\CurrenciesExportController\CurrenciesExportRequest;
use App\Services\CurrenciesExportService;


class CurrenciesExportController{


    private $currenciesExportService;



    /**
     * CurrenciesExportController constructor.
     *
     * @param CurrenciesExportService $currenciesExportService
    */
    public function __construct(CurrenciesExportService $currenciesExportService){
        $this->currenciesExportService = $currenciesExportService;
        Log::info('CurrenciesExportController initialized');
    }



    /**
     * Display the form for exporting currencies.
    */
    public function showCurrenciesExportForm(){
        Log::info('Displaying the currencies export form');
        return view('currencies_export');
    }


    
    /**
     * Export currencies data to an Excel file.
     *
     * @param CurrenciesExportRequest $request
     * @return RedirectResponse
    */
    public function exportToExcel(CurrenciesExportRequest $request){

        $fileName = $request->input('excel_file_name');
        
        Log::info('Starting currency export to Excel', ['fileName' => $fileName]);

        try {
            // Call the service to export currencies to an Excel file
            $this->currenciesExportService->exportToExcel($fileName);

            return redirect()->back()->with('success', 'Currencies exported to Excel successfully.');
        } catch (\Exception $e) {

            Log::error('Currency export to Excel failed', ['error' => $e->getMessage(), 'fileName' => $fileName]);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }



    /**
     * Export currencies data to a database.
     *
     * @param CurrenciesExportRequest $request
     * @return RedirectResponse
    */
    public function exportToDatabase(CurrenciesExportRequest $request){
        
        $data = [
            'host' => $request->input('db_host'),
            'port' => $request->input('db_port'),
            'database' => $request->input('db_name'),
            'username' => $request->input('db_username'),
            'password' => $request->input('db_password'),
            'table' => $request->input('db_table_name') ?? 'currencies',
        ];

        Log::info('Starting currency export to database', ['data' => $data]);


        try {
            // Call the service to export currencies to a database
            $this->currenciesExportService->exportToDatabase($data);

            Log::info('Currency export to database successful', ['data' => $data]);

            return redirect()->back()->with('success', 'Data exported successfully to the database.');
        } catch (\Exception $e) {

            Log::error('Currency export to database failed', ['error' => $e->getMessage(), 'data' => $data]);

            return redirect()->back()->with('error', 'Failed to export data: ' . $e->getMessage());
        }
    }
}

?>
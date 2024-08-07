<?php 

namespace App\Http\Requests\CurrenciesExportController;

use Illuminate\Foundation\Http\FormRequest;


class CurrenciesExportRequest extends FormRequest{

    public function rules()
    {
        return [

            'excel_file_name' => 'nullable|string|max:255',

            // Validation rules for exporting to Database
            'db_host' => 'required_if:export_database,on|string|max:255',
            'db_port' => 'required_if:export_database,on|numeric|min:1|max:65535',
            'db_name' => 'required_if:export_database,on|string|max:255',
            'db_table_name' => 'nullable|string|max:255',
            'db_username' => 'required_if:export_database,on|string|max:255',
            'db_password' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
        ];
    }

}

?>
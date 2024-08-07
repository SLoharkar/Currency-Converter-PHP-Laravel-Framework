<?php 

namespace App\Http\Requests\IPController;

use Illuminate\Foundation\Http\FormRequest;


class IPRequest extends FormRequest{

    public function rules()
    {
        return [
            'ip_address' => 'required|string|regex:/^(\d{1,3}\.){3}\d{1,3}(\/\d{1,2})?$/',
        ];
    }

    public function messages()
    {
        return [
            'ip_address.required' => 'The ip field is required.',
        ];
    }

}

?>
<?php 

namespace App\Http\Requests\AdminController;

use Illuminate\Foundation\Http\FormRequest;


class UserUpdateRequest extends FormRequest{

    public function rules()
    {
        return [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:3',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'The username field is required.',
            'password.required' => 'The password field is required.',
        ];
    }

}

?>
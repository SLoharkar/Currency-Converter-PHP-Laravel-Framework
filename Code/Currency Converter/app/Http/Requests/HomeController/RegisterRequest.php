<?php 

namespace App\Http\Requests\HomeController;

use Illuminate\Foundation\Http\FormRequest;


class RegisterRequest extends FormRequest{

    public function rules()
    {
        return [
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'The username field is required.',
            'username.unique' => 'Username already taken.',
            'password.required' => 'The password field is required.',
        ];
    }

}

?>
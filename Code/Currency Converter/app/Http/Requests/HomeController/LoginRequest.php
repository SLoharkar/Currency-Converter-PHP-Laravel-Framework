<?php 

namespace App\Http\Requests\HomeController;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;


class LoginRequest extends FormRequest{

    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'The username field is required.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if there are any users in the database
            if (User::count() == 0) {
                $validator->errors()->add('username', 'No users found in the database.');
            }
        });
    }

}

?>
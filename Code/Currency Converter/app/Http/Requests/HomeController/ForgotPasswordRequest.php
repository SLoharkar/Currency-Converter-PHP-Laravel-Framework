<?php 

namespace App\Http\Requests\HomeController;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class ForgotPasswordRequest extends FormRequest{

    public function rules()
    {
        return [
            'username' => 'required|string|max:255|exists:users,username',
            'ext_password' => 'required|string',
            'new_password' => 'required|string|min:6',
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


    public function messages()
    {
        return [
            'username.required' => 'The username field is required.',
            'username.exists' => 'The provided username does not exist.',
        ];
    }



}

?>
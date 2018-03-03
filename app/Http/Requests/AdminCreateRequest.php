<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|max:30',
            'password' => 'required|max:30',
            'last_name' => 'required|max:5',
            'first_name' => 'required|max:5',
            'nickname' => 'required|max:8',
            'email' => 'required|email',
            'isAdmin'=> 'required|integer'
        ];
    }
}

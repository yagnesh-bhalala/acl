<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsersRequest extends FormRequest
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
            'name' => 'required',
            'username' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/|unique:users,username',
            'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@£%^&*]).*$/',
            // 'roles' => 'required'
            'start_access_date' => 'required|date',
            'end_access_date' => 'required|date|after_or_equal:start_access_date',
            'phone' => 'nullable|numeric|digits:10'
        ];
    }

    public function messages()
    {
        return [
            'password.regex'  => 'Password contain, At least 1 capital letter, At least 1 lowercase letter, At least 1 special character, At least 1 numeric character',
            'username.regex'  => 'Username have, At least 1 letter, At least 1 numeric character',
        ];
    }
}

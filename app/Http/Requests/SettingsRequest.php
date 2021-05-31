<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;  // temp value
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method()) {
            case 'GET':
            case 'DELETE': { return []; }             break;

            case 'POST': {
                return [
                    'name' => 'required|max:255|unique:settings',
                    'value' => 'required',
                ];
            }
            break;
            case 'PUT':
            case 'PATCH': {
                return [
                    'name' => 'required|max:255|unique:settings,name,'.$this->id,
                    'value' => 'required',
                ];
            }
            break;
            default:break;
        }
    }
}

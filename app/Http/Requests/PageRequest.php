<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
        switch($this->method()) {
            case 'GET':
            case 'DELETE': return [];
                
            case 'POST': {
                return [
                    'link' => 'required|max:255|unique:pages',
                    'title' => 'required|max:255',
                    'description' => 'required|max:255',
                    'keywords' => 'required|max:255',
                ];
            }
            
            case 'PUT':
            case 'PATCH': {
                return [
                    'link' => 'required|max:255|unique:pages,link,'.$this->id,
                    'title' => 'required|max:255',
                    'description' => 'required|max:255',
                    'keywords' => 'required|max:255',
                ];
            }
            default:break;
        }
    }
}

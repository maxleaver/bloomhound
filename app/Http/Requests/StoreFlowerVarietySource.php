<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFlowerVarietySource extends FormRequest
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
            '*.vendor_id' => 'nullable|required_without:*.vendor_name||integer',
            '*.vendor_name' => 'nullable|required_without:*.vendor_id|string',
            '*.cost' => 'required|numeric',
            '*.stems_per_bunch' => 'required|integer',
        ];
    }
}

<?php

namespace App\Http\Requests\Api\Records;

use Illuminate\Foundation\Http\FormRequest;

class RecordStoreRequest extends FormRequest
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
            'type' => ['required', 'string'],
            'value' => ['required', 'string'],
            'order' => 'nullable|numeric|min:0',
            'record_category_id' => ['nullable']
        ];
    }
}

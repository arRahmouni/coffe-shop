<?php

namespace App\Http\Requests;

use app\Http\Requests\Api\BaseApiRequest;

class StoreCategoryRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'      =>  ['required', 'string', 'max:255'],
            'slug'      =>  ['required', 'string', 'max:255'],
            'is_active' =>  ['required', 'boolean'],
            'image'     =>  ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:10240'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}

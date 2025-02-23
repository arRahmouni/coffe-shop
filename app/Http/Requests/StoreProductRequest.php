<?php

namespace App\Http\Requests;

use app\Http\Requests\Api\BaseApiRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'slug'           => ['required', 'string', 'max:255'],
            'description'    => ['required', 'string', 'max:2000'],
            'image'          => [$this->isCreate() ? 'required' : 'nullable', File::image()->max('10mb')],
            'price'          => ['required', 'numeric', 'min:1', 'max:99999999.99'],
            'is_active'      => ['required', 'boolean'],
            'category_ids'   => ['required', 'array', 'min:1'],
            'category_ids.*' => ['required' , Rule::exists('categories', 'id')->where('user_id', request()->user()->id)],
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

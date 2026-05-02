<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PastebinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'description'  => 'nullable|string|max:1000',
            'banner'       => 'nullable|image|max:5120',
            'gallery.*'    => 'nullable|image|max:5120',
            'categories'   => 'nullable|string',
            'language'     => 'nullable|string|max:50',
            'visibility'   => 'nullable|in:public,unlisted,private',
            'expiry'       => 'nullable|in:never,10min,1hour,1day,1week,1month,1year',
            'password'     => 'nullable|string|max:100',
            'syntax_theme' => 'nullable|string|max:50',
        ];
    }
}

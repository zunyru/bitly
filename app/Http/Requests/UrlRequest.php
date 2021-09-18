<?php

namespace App\Http\Requests;


use App\Rules\Blacklist;
use Illuminate\Foundation\Http\FormRequest;

class UrlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $uniqueCode = '|unique:shorturl_urls';

        if ($this->route('id')) {
            $uniqueCode .= ',id,' . $this->route('id');
        }

        return [
            'url'        => ['required', 'url', new Blacklist()],
            'code'       => 'max:255' . $uniqueCode.'|regex:/^[a-zA-Z0-9 ]+$/|nullable',
            'expires_at' => 'date|after:now|nullable',
        ];
    }
}

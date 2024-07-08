<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessFileRequest extends FormRequest
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
            'input_file' => 'required|file|mimes:csv,txt|max:102400', 
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'input_file.required' => 'O arquivo é obrigatório.',
            'input_file.file' => 'O arquivo deve ser um arquivo válido.',
            'input_file.mimes' => 'O arquivo deve ser um arquivo do tipo: csv, txt.',
            'input_file.max' => 'O arquivo não deve exceder 100MB.',
        ];
    }
}


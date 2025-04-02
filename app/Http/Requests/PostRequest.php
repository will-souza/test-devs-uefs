<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório',
            'content.required' => 'O conteúdo é obrigatório',
            'user_id.required' => 'O autor é obrigatório',
            'user_id.exists' => 'O autor selecionado é inválido',
            'tags.array' => 'As tags devem ser um array',
            'tags.*.exists' => 'Uma ou mais tags selecionadas são inválidas',
        ];
    }
}

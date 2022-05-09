<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReactionRequest extends FormRequest
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
            'post_id' => 'required|int|exists:posts,id',
            'like'    => 'required|boolean'
        ];
    }

    /**
     * Define the custom messages for validation error.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'post_id.required'    => 'post_id is required',
            'post_id.int'         => 'post_id must be integer',
            'like.required'       => 'reaction is required',
            'like.boolean'        => 'reaction must be boolean'
        ];
    }
}

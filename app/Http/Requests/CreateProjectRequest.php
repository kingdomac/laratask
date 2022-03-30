<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
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
            'agent_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role_id', '=', RoleEnum::AGENT->value);
                })
            ],
            'name' => ['required'],
            'website' => ['nullable', 'url'],
            'budget' => ['nullable', 'numeric'],
            'due_date' => ['nullable', 'date:Y-m-d'],
            'description' => ['nullable', 'string']
        ];
    }
}

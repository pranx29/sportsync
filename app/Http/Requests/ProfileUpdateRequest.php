<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Models\User;
use App\Models\Department;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['nullable', 'string', 'max:255'],
            'department' => ['required', 'exists:' . Department::class . ',id'],
            'jobTitle' => ['required', 'exists:' . Role::class . ',id'],
            'dateOfBirth' => ['required', 'date'],
            'picture' => ['image', 'max:2048'],
        ];
    }
}

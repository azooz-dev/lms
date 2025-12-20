<?php

declare(strict_types=1);

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleId = $this->route('id');

        return [
            'name' => 'required|string|max:255|unique:roles,name,'.$roleId,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The role name is required.',
            'name.unique' => 'This role already exists.',
            'name.max' => 'The role name must not exceed 255 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Role Name',
        ];
    }
}


<?php

declare(strict_types=1);

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $permissionId = $this->route('id');

        return [
            'name' => 'required|string|max:255|unique:permissions,name,'.$permissionId,
            'group_name' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The permission name is required.',
            'name.unique' => 'This permission already exists.',
            'name.max' => 'The permission name must not exceed 255 characters.',
            'group_name.required' => 'The group name is required.',
            'group_name.max' => 'The group name must not exceed 255 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Permission Name',
            'group_name' => 'Group Name',
        ];
    }
}

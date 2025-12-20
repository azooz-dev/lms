<?php

declare(strict_types=1);

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class ImportPermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ];
    }

    public function messages(): array
    {
        return [
            'excel_file.required' => 'Please select an Excel file to import.',
            'excel_file.file' => 'The uploaded file is invalid.',
            'excel_file.mimes' => 'The file must be an Excel file (.xlsx or .xls).',
        ];
    }

    public function attributes(): array
    {
        return [
            'excel_file' => 'Excel File',
        ];
    }
}

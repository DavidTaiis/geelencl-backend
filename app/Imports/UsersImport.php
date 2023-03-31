<?php

namespace App\Imports;

use App\Models\User;
use App\Repositories\UserRepository;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable , SkipsFailures;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
         UserRepository::saveUpload($row);
    }

    public function rules(): array
    {
        return [
            '*.code_company' => 'required|string|exists:company,code',
            '*.name' => 'required',
            '*.email' => 'required|email',
            '*.password' => 'required',
            '*.role' => 'required|exists:roles,name',
            '*.cedi' => 'required'
        ];
    }
    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'code_company.required' => 'El código de la empresa es requerido',
            'code_company.exists' => 'El código de la empresa no existe',
            'role.exists' => 'El rol no existe',
        ];
    }

}

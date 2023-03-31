<?php

namespace App\Imports;

use App\Models\RedemptionsByUsers;
use App\Repositories\RedeemedProductRepository;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class RedemptionTrayImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    use Importable , SkipsFailures;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        RedeemedProductRepository::saveUpload($row);
    }

    public function rules(): array
    {
        return [
            '*.canjeo' => 'required|string',
            '*.status' => 'required',
            '*.status_tracking' => 'required|exists:tracking_status.name',
        ];
    }
    /**
     * @return array
     */
    public function customValidationMessages()
    {
    }

}

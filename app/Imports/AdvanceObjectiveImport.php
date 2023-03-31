<?php

namespace App\Imports;

use App\Models\ImportAdvanceLog;
use App\Models\Product;
use App\Repositories\CampaignRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AdvanceObjectiveImport implements ToModel, WithHeadingRow
{
    use Importable;

    public $modelImportLog;

    public function __construct(ImportAdvanceLog $model)
    {
        $this->modelImportLog = $model;
    }

    public function model(array $row)
    {
        CampaignRepository::saveAdvance($row, $this->modelImportLog);
    }
}

<?php

namespace App\Imports;

use App\Models\Product;
use App\Repositories\MissionRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserObjectivesImport implements ToModel, WithHeadingRow
{
    use Importable;

    public $objectiveId;
    public $missionId;
        
    public function __construct($objectiveId, $missionId)
    {
        $this->objectiveId = $objectiveId;
        $this->missionId = $missionId;
    }
    public function model(array $row)
    {
        MissionRepository::saveUserObjectives($row, $this->objectiveId , $this->missionId);
    }
}

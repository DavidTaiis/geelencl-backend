<?php

namespace App\Exports;

use App\Models\ImportAdvanceItemLog;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DetailsAdvanceExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $querySearch;
    protected $id;

    public function __construct($querySearch, $id)
    {
        $this->querySearch = $querySearch;
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'code_company',
            'code_mission',
            'email_customer',
            'objective_code',
            'objective_value',
            'last_advance_objective',
            'last_advance_mission',
            'date_creation'
        ];
    }

    /**
     * @param mixed $item
     * @return array
     */
    public function map($item): array
    {
        return [
            $item->code_company,
            $item->code_mission,
            $item->email_customer,
            $item->objective_code,
            $item->objective_value,
            $item->last_advance_objective,
            $item->last_advance_mission,
            $item->created_at
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query()
    {
        $query = ImportAdvanceItemLog::query()->where('import_advance_log_id' , $this->id);
        if ($this->querySearch != "" || $this->querySearch != null) {
            $search = $this->querySearch;
            $query->where(function ($subQuery) use ($search) {
                $subQuery->orwhere('import_advance_item_logs.objective_code', 'like', "$search%");
                $subQuery->orwhere('import_advance_item_logs.email_customer', 'like', "$search%");
            });
           
        }
        $query->orderBy('created_at', 'DESC');

        return $query;
    }
}

<?php

namespace App\Exports;

use App\Models\AdvanceObjective;
use App\Models\RedemptionsByUsers;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PointsWinExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $queryParams;

    public function __construct($queryParams)
    {
        $this->queryParams = $queryParams;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'usuario',
            'email',
            'puntos',
            'objetivo',
            'mision',
            'fecha'
        ];
    }

    /**
     * @param mixed $item
     * @return array
     */
    public function map($item): array
    {
        return [
            $item->userName,
            $item->userEmail,
            $item->points_win,
            $item->missionName,
            $item->campaignName,
            $item->created_at
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query()
    {
        $data = $this->queryParams;
        $companyId = Auth::user()->company_id ?? null;
        $query = AdvanceObjective::query();
        $query->select([
            'advance_objectives_by_users.id as id',
            'advance_objectives_by_users.points_win as points_win',
            'advance_objectives_by_users.created_at as created_at',
            'missions.id as missionId',
            'missions.name as missionName',
            'campaign.id as campaignId',
            'campaign.name as campaignName',
            'users.name as userName',
            'users.email as userEmail'
        ]);
        $query->join('users', 'users.id', '=', 'advance_objectives_by_users.users_id');
        $query->join('missions', 'missions.id', '=', 'advance_objectives_by_users.missions_id');
        $query->join('campaign', 'campaign.id', '=', 'missions.campaign_id');
        $query->where('advance_objectives_by_users.points_win', '>', 0);
        if ($companyId != null) {
            $query->where('users.company_id', '=', $companyId);
        }


        if (isset($data['dateStart']) && $data['dateStart'] && !(isset($data['dateEnd']) && $data['dateEnd'])) {
            $dateStar = $data['dateStart'];
            $query->where('advance_objectives_by_users.created_at', '>=', $dateStar . ' 00:00:00');
        }
        if (!(isset($data['dateStart']) && $data['dateStart']) && (isset($data['dateEnd']) && $data['dateEnd'])) {
            $endDate = $data['dateEnd'];
            $query->where('advance_objectives_by_users.created_at', '<=', $endDate . ' 23:59:59');
        }
        if (isset($data['dateStart']) && $data['dateStart'] && isset($data['dateEnd']) && $data['dateEnd']) {
            $dateStar = $data['dateStart'];
            $dateEnd = $data['dateEnd'];
            $query->whereBetween('advance_objectives_by_users.created_at', [$dateStar . ' 00:00:00', $dateEnd . ' 23:59:59']);
        }

        if (isset($data['userId']) && $data['userId']) {
            $userId = $data['userId'];
            $query->whereIn('advance_objectives_by_users.users_id', $userId);
        }
        if (isset($data['objectiveId']) && $data['objectiveId']) {
            $objectiveId = $data['objectiveId'];
            $query->whereIn('advance_objectives_by_users.missions_id', $objectiveId);
        }
        if (isset($data['campaignId']) && $data['campaignId']) {
            $campaignId = $data['campaignId'];
            $query->whereIn('campaign.id', $campaignId);
        }
        $query->groupBy('advance_objectives_by_users.id');
        $query->orderBy('advance_objectives_by_users.created_at', 'desc');
        return $query;
    }
}

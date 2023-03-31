<?php

namespace App\Exports;

use App\Models\RedemptionsByUsers;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RedemptionTrayExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $querySearch;
    protected $dateStart;
    protected $dateEnd;
    protected $status;
    protected $trackingStatus;

    public function __construct($querySearch, $dateStart, $dateEnd, $status, $trackingStatus)
    {
        $this->querySearch = $querySearch;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->status = $status;
        $this->trackingStatus = $trackingStatus;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'canjeo',
            'company',
            'customer',
            'cedi',
            'email_customer',
            'product_code',
            'product',
            'points',
            'redeemed_date',
            'status',
            'status_tracking',
        ];
    }

    /**
     * @param mixed $item
     * @return array
     */
    public function map($item): array
    {
        return [
            $item->id,
            $item->user->company->name,
            $item->user->name,
            $item->user->cedi,
            $item->user->email,
            $item->details[0]->product->code,
            $item->details[0]->product->name,
            $item->details[0]->product->minimum_points_required,
            $item->created_at,
            $item->status,
            $item->trackingStatus->name,
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query()
    {
        $query = RedemptionsByUsers::query();
        $query->with(['user' => function ($subQuery) {
            $subQuery->with(['company']);
        }]);
        $query->with(['details' => function ($subQuery) {
            $subQuery->with(['product']);
        }]);
        $query->with(['trackingStatus']);
        if (Auth::user()->company_id != null) {
            $query->where(function ($subQuery) {
                $subQuery->orWhereHas('user', function ($querySub) {
                    $querySub->whereHas('company', function ($queryCompany) {
                        $queryCompany->where('company.id', Auth::user()->company_id);
                    });
                });
            });
        }

        if ($this->dateStart != "" || $this->dateStart != null && $this->dateEnd != "" || $this->dateEnd != null) {
            $dateStar = $this->dateStart;
            $dateEnd = $this->dateEnd;
            $query->where(function ($subQuery) use ($dateStar, $dateEnd) {
                $subQuery->WhereBetween('redemptions_by_users.created_at', [$dateStar . ' 00:00:00', $dateEnd . ' 23:59:59']);
            });
        }

        if ($this->status != "" || $this->status != null) {
            $status = $this->status;
            $query->where(function ($subQuery) use ($status) {
                $subQuery->where('redemptions_by_users.status', $status);
            });
        }
        if ($this->trackingStatus != "" || $this->trackingStatus != null) {
            $trackingStatus = $this->trackingStatus;
            $query->where(function ($subQuery) use ($trackingStatus) {
                $subQuery->where('redemptions_by_users.tracking_status_id', $trackingStatus);
            });
        }
        if ($this->querySearch != "" || $this->querySearch != null) {
            $search = $this->querySearch;
            $query->where(function ($subQuery) use ($search) {
                $subQuery->orWhereHas('user', function ($querySub) use ($search) {
                    $querySub->where('users.name', 'like', "%$search%");
                    $querySub->orWhere('users.email', 'like', "%$search%");
                    $querySub->orWhereHas('company', function ($queryCompany) use ($search) {
                        $queryCompany->where('company.name', 'like', "%$search%");
                    });
                });
            });
           
        }
        $query->orderBy('created_at', 'DESC');

        return $query;
    }
}

<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Campaign;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserObjectivesExport implements WithHeadings, FromArray
{
    use Exportable;

    protected $campaignId;

    public function __construct($campaignId)
    {


        $this->campaignId = $campaignId;
    }

    public function array(): array
    {
        $campaign = Campaign::find($this->campaignId);
        $companyId = $campaign->company_id;
        $users = User::query()
        ->where('company_id' , $companyId)
        ->where('status' , User::STATUS_ACTIVE )->get();
        $data = [];

        foreach ($users as $user) {
            if($user->hasRole(config('constants.roles.role_client'))){
                $data[] = [
                    $user->email,
                    '0',
                ];
            }
        }
        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return ['email', 'value_goal'];
    }
}

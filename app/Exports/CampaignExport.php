<?php

namespace App\Exports;

use App\Models\Mission;
use App\Models\Campaign;
use App\Models\Company;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CampaignExport implements WithHeadings, FromArray
{
    use Exportable;

    protected $campaignId;

    /**
     * CampaignExport constructor.
     */
    public function __construct($campaignId)
    {


        $this->campaignId = $campaignId;
    }

    public function array(): array
    {
        $campaign = Campaign::find($this->campaignId);
        $missions = $campaign->missions;
        $data = [];

        foreach ($missions as $mission) {
            $data[] = [
                $campaign->company->code,
                $campaign->code,
                'example@gmail.com',
                $mission->code,
                0,
            ];
        }
        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return ["code_company", "code_mission", 'email_customer', 'objective_code', 'objective_value'];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BosDashboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $period = $this['period'];

        return [
            'header' => [
                'period_id' => $period->id,
                'period_code' => $period->code,
                'period_name' => $period->name,
                'status' => $period->status,
                'authority' => $period->authority,
                'regulation_version' => $period->regulation_version,
                'school_name' => $period->school?->name,
                'school_npsn' => $period->school?->npsn,
                'academic_year' => $period->academicYear?->name,
            ],
            'kpi_cards' => $this['kpi_cards'],
            'siplah_banner' => $this['siplah_banner'],
            'transaction_tabs' => $this['transaction_tabs'],
            'transactions' => BosTransactionResource::collection($this['transactions'])->response()->getData(true),
            'snp_progress' => $this['snp_progress'],
            'compliance_box' => $this['compliance_box'],
            'deadline_notice' => $this['deadline_notice'],
        ];
    }
}
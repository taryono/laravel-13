<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BosTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $standardName = $this->rkasRealization?->item?->subActivity?->activity?->program?->standard?->name;
        $componentName = $this->usagePlan?->component?->name;

        return [
            'id' => $this->id,
            'date' => $this->realization_date?->toDateString(),
            'date_formatted' => $this->realization_date?->translatedFormat('d M Y'),
            'receipt_number' => $this->receipt_number,
            'description' => $this->description,
            'vendor_name' => $this->vendor?->name ?? 'Pembayaran Langsung',
            'is_siplah' => (bool) $this->is_siplah,
            'component_badge' => $componentName ?? $standardName ?? 'Umum',
            'amount' => (float) $this->amount,
            'tax_amount' => (float) $this->tax_amount,
            'total_amount' => (float) $this->amount + (float) $this->tax_amount,
            'tax_type' => $this->tax_type,
            'tax_status' => $this->tax_status,
            'has_attachment' => !empty($this->attachment_url),
            'attachment_url' => $this->attachment_url,
            'journal_entry_id' => $this->journal_entry_id,
        ];
    }
}
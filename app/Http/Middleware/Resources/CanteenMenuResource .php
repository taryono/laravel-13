<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CanteenMenuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isOwner = $request->attributes->get('tenant_role') === 'owner';

        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'barcode'      => $this->barcode,
            'category'     => $this->category,
            'price'        => $this->price,
            // Harga modal hanya untuk pemilik, kasir tidak perlu melihatnya.
            'cost_price'   => $this->when($isOwner, $this->cost_price),
            'photo_url'    => $this->photo_url,
            'is_available' => $this->is_available,
        ];
    }
}
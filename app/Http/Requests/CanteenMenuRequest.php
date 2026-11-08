<?php

namespace App\Http\Requests;

use Models\CanteenMenu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CanteenMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // hak akses sudah dijaga middleware tenant.role:owner
    }

    protected function prepareForValidation(): void
    {
        // Scanner kadang menambah spasi/baris baru.
        $barcode = trim((string) $this->input('barcode', ''));
        $this->merge(['barcode' => $barcode === '' ? null : $barcode]);
    }

    public function rules(): array
    {
        $tenantId = (int) $this->route('tenant');
        $menuId   = $this->route('menu');

        return [
            'name'         => ['required', 'string', 'max:150'],
            'barcode'      => [
                'nullable', 'string', 'max:64', 'regex:/^[A-Za-z0-9\-]+$/',
                Rule::unique('canteen_menus', 'barcode')
                    ->where('canteen_tenant_id', $tenantId)
                    ->whereNull('deleted_at')
                    ->ignore($menuId),
            ],
            'category'     => ['required', Rule::in(CanteenMenu::CATEGORIES)],
            'price'        => ['required', 'numeric', 'gt:0', 'max:99999999'],
            'cost_price'   => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'photo_url'    => ['nullable', 'url', 'max:2048'],
            'is_available' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'barcode.unique' => 'Barcode ini sudah dipakai menu lain di kantin Anda.',
            'barcode.regex'  => 'Barcode hanya boleh huruf, angka, dan tanda minus.',
        ];
    }
}
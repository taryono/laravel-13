<?php

namespace App\Http\Controllers;

use App\Filters\GlobalSearch;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Lib\FormLib;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
class MainController extends Controller implements HasMiddleware
{
    protected $model;
    protected $controller;

    // FIX: beri default null supaya class ini (dan turunannya yang tidak
    // override constructor) tetap bisa di-resolve otomatis oleh container,
    // dan controller yang tidak butuh $model tidak wajib pass argumen.
    public function __construct($model = null, $controller = null)
    {
        $this->model = $model;
        if ($controller) {
            $this->controller = $controller;
        } else {
            $this->controller = Str::snake($model ? $model->getModel()->getTable() : '');
        }
        $activeOrganization = session()->get('active_organization');
        // Bagikan ke tampilan
        view()->share('activeOrganization', $activeOrganization);
    }

    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    /**
     * Generate field form dari sebuah model/object.
     *
     * FIX: sebelumnya hanya me-return $forms->getForms(), sehingga field yang
     * dikelompokkan lewat setGroups() hilang tanpa cara mengaksesnya dari
     * controller/blade. Sekarang return array asosiatif berisi 'forms' (field
     * biasa) dan 'grouped' (field yang dikelompokkan), supaya keduanya bisa
     * dirender terpisah di blade.
     *
     * @param object $model     model/instance sumber field
     * @param array  $groups    (opsional) daftar field yang mau dikelompokkan
     * @param array  $customFields (opsional) daftar field yang mau ditampilkan saja
     */
    public function getForm($model, array $groups = [], array $customFields = []): array
    {
        $formLib = new FormLib($model, $model, $customFields);

        if (!empty($groups)) {
            $formLib->setGroups($groups);
        }

        $formLib->getFields();

        return [
            'forms' => $formLib->getForms(),
            'grouped' => $formLib->getGroupedForms(),
        ];
    }

    /**
     * Return successful response.
     */
    protected function successResponse(
        Request $request,
        string $message,
        $data = null,
        ?string $redirectTo = null
    ): JsonResponse|RedirectResponse {

        if ($this->isAjaxRequest($request)) {
            $response = ['success' => true, 'message' => $message];

            if ($data !== null) {
                $response['data'] = $data;
            }
            if ($redirectTo) {
                $response['redirectTo'] = $redirectTo;
            }

            return response()->json($response);
        }

        return redirect($redirectTo ?? route($this->controller . '.index'))
            ->with('success', $message);
    }

    /**
     * Return failed response.
     */
    protected function errorResponse(
        Request $request,
        string $message,
        int $status = 500
    ): JsonResponse|RedirectResponse {

        if ($this->isAjaxRequest($request)) {

            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $message);
    }

    /**
     * Determine whether the request expects an AJAX response.
     */
    protected function isAjaxRequest(Request $request): bool
    {
        return $request->ajax() || $request->expectsJson();
    }

    protected function flattenForSelect($tree, int $depth = 0): array
    {
        $result = [];
        foreach ($tree as $item) {
            $result[] = [
                'id' => $item->id,
                'label' => str_repeat('— ', $depth) . $item->title,
                'icon' => $item->icon,
                'icon_type' => $item->iconType,
                'depth' => $depth,
            ];
            if (!empty($item->children)) {
                $result = array_merge($result, $this->flattenForSelect($item->children, $depth + 1));
            }
        }
        return $result;
    }

    public function query($model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with('parent');

        if (request()->filled('is_active')) {
            $query->where('is_active', request('is_active'));
        }

        if (request()->filled('is_system')) {
            $query->where('is_system', request('is_system'));
        }

        if (request()->filled('parent_id')) {
            $query->where('parent_id', request('parent_id'));
        }

        GlobalSearch::search(
            $query,
            ['parent'],
            ['code', 'name', 'display_name', 'description']
        );

        return $query;
    }
}

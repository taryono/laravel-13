<?php

namespace App\Modules\Slider\Controllers;

use App\DataTables\SliderDataTable;
use App\Http\Controllers\MainController;
use Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class SliderController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Slider());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     * - Normal page request
     * - Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        SliderDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /slider?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Slider::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $slider = new Slider();
        return view(
            'Slider::create',
            compact('slider')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $this->validated($request);

        try {
            if ($validated['type'] === 'image' && $request->hasFile('image')) {
                $validated['image_path'] = $request->file('image')->store('sliders', 'public');
            }


            $model = DB::transaction(function () use ($validated) {
                return Slider::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Slider berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Slider.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Slider gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $slider = $this->model->findOrFail($id);
        return view(
            'Slider::edit',
            compact('slider')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $this->validated($request);

        try {
            $slider = $this->model->findOrFail($id);

            if ($validated['type'] === 'image' && $request->hasFile('image')) {
                if ($slider->image_path) Storage::disk('public')->delete($slider->image_path);
                $validated['image_path'] = $request->file('image')->store('sliders', 'public');
            }


            DB::transaction(function () use (
                $slider,
                $validated
            ) {
                $slider->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Slider berhasil diperbarui.',
                $slider
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Slider.',
                [
                    'exception' => $e,
                    'Slider_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Slider gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $slider = $this->model->findOrFail($id);

            DB::transaction(function () use ($slider) {
                $slider->delete();
            });

            return $this->successResponse(
                $request,
                'Data Slider berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $slider->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Slider.',
                [
                    'exception' => $e,
                    'Slider_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Slider gagal dihapus.'
            );
        }
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'        => 'nullable|string|max:255',
            'type'         => 'required|in:image,html',
            'image'        => 'nullable|image|max:4096',
            'blade_view'   => 'nullable|string|max:255',
            'html_content' => 'nullable|string',
            'alt_text'     => 'nullable|string|max:255',
            'link_url'     => 'nullable|string|max:500',
            'link_target'  => 'nullable|in:_self,_blank',
            'order'        => 'nullable|integer',
            'is_active'    => 'boolean',
            'start_at'     => 'nullable|date',
            'end_at'       => 'nullable|date|after_or_equal:start_at',
            'settings'     => 'nullable|array',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order']     = $request->input('order', 0);
        $data['link_target'] = $request->input('link_target', '_self');

        return $data;
    }
}

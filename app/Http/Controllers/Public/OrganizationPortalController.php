<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Models\CmsComment;
use Models\CmsPost;
use Models\Facility;
use App\Support\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;

class OrganizationPortalController extends Controller
{
    public function index(Request $request): View
    {
        $posts = CmsPost::with('category')
            ->published()
            ->latest('published_at')
            ->take(3)
            ->get();

        $facilitiesQuery = Facility::with(['primaryImage', 'images'])->active();
        if ($orgId = TenantContext::id()) {
            $facilities = (clone $facilitiesQuery)->where('organization_id', $orgId)->orderBy('sort_order')->take(8)->get();
            if ($facilities->isEmpty()) {
                $facilities = $facilitiesQuery->orderBy('sort_order')->take(8)->get();
            }
        } else {
            $facilities = $facilitiesQuery->orderBy('sort_order')->take(8)->get();
        }

        return view('tenant.index', compact('posts', 'facilities'));
    }

    public function news(Request $request): View
    {
         return view('tenant.news');
    }

    public function profile(Request $request): View
    {
         return view('tenant.profile');
    }
}
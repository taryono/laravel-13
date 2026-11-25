<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Models\CmsComment;
use Models\CmsPost;
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
         return view('tenant.index');
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
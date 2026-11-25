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

class NewsCommentController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $post = CmsPost::published()
            ->where('slug', $slug)
            ->firstOrFail();

        if (!$post->allow_comments) {
            return back()->with('comment_error', 'Komentar telah ditutup untuk artikel ini.');
        }

        if ($request->filled('website_hp')) {
            return back()->with('comment_success', 'Terima kasih, komentar Anda telah dikirim dan menunggu moderasi.');
        }

        $isAuthenticated = Auth::check();

        $validated = $request->validate([
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('cms_comments', 'id')->where(
                    fn($q) => $q->where('post_id', $post->id)->where('status', 'approved')
                ),
            ],
            'author_name' => [$isAuthenticated ? 'nullable' : 'required', 'string', 'max:191'],
            'author_email' => [$isAuthenticated ? 'nullable' : 'required', 'email', 'max:191'],
            'content' => ['required', 'string', 'min:3', 'max:1500'],
        ], [
            'author_name.required' => 'Nama lengkap wajib diisi.',
            'author_email.required' => 'Alamat email wajib diisi.',
            'author_email.email' => 'Format email tidak valid.',
            'content.required' => 'Isi komentar tidak boleh kosong.',
            'content.min' => 'Komentar minimal 3 karakter.',
        ]);

        $autoApprove = $isAuthenticated;

        CmsComment::create([
            'post_id' => $post->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'user_id' => Auth::id(),
            'name' => $isAuthenticated ? Auth::user()->name : strip_tags($validated['author_name']),
            'email' => $isAuthenticated ? Auth::user()->email : $validated['author_email'],
            'body' => strip_tags($validated['content']),
            'status' => $autoApprove ? 'approved' : 'pending',
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 191, ''),
        ]);

        $message = $autoApprove
            ? 'Komentar Anda berhasil dipublikasikan.'
            : 'Terima kasih! Komentar Anda telah terkirim dan akan tampil setelah dimoderasi admin.';

        return back()->with('comment_success', $message)->withFragment('comments-section');
    }
}
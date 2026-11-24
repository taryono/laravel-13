<?php

namespace App\Http\Requests;

use App\Enums\PostStatus;
use App\Models\CmsPost;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mews\Purifier\Facades\Purifier;

class CmsPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $postId = $this->route('cms_post')?->id ?? $this->route('cms_post');

        return [
            'cms_category_id' => ['nullable', 'exists:cms_categories,id'],
            'title' => ['required', 'string', 'max:191'],
            'slug' => [
                'required',
                'string',
                'max:191',
                'alpha_dash',
                Rule::unique('cms_posts', 'slug')
                    ->whereNull('deleted_at')
                    ->ignore($postId),
            ],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
            'status' => ['required', Rule::enum(PostStatus::class)],
            'published_at' => ['nullable', 'required_if:status,scheduled', 'date'],
            'is_featured' => ['boolean'],
            'allow_comments' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:191'],
            'meta_description' => ['nullable', 'string', 'max:191'],
            'meta_keywords' => ['nullable', 'string', 'max:191'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $postId = $this->route('cms_post')?->id ?? $this->route('cms_post');
        $rawSlug = trim((string) $this->input('slug'));

        $this->merge([
            'slug' => $rawSlug !== '' ? str($rawSlug)->slug()->toString() : CmsPost::generateUniqueSlug((string) $this->input('title'), $postId),
            'is_featured' => $this->boolean('is_featured'),
            'allow_comments' => $this->boolean('allow_comments'),
        ]);
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        if (isset($data['content'])) {
            $data['content'] = Purifier::clean($data['content'], 'cms_content');
        }

        if (isset($data['excerpt'])) {
            $data['excerpt'] = strip_tags($data['excerpt']);
        }

        return $data;
    }
}
<?php

namespace Models;

use Illuminate\Support\Facades\Storage;

class TemplateAsset extends SoftDeletableModel
{
    protected $table = 'template_assets';

    protected $guarded = ['id'];

    protected $appends = ['url'];

    public function template()
    {
        return $this->belongsTo(ReportTemplate::class, 'template_id');
    }

    public function getUrlAttribute(): string
    {
        if (empty($this->file_path)) {
            return '';
        }
        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }
        return Storage::disk(config('report.asset_disk', 'public'))->url($this->file_path);
    }
}

if (!class_exists('App\Models\TemplateAsset', false)) {
    class_alias(TemplateAsset::class, 'App\Models\TemplateAsset');
}

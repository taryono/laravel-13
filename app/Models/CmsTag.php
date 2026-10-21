<?php
namespace Models; 

use Illuminate\Database\Eloquent\Model;

class CmsTag extends Model
{
    protected $table = 'cms_tags';
    protected $guarded = ['id'];

    // Relasi
    public function posts()
    {
        return $this->belongsToMany(CmsPost::class, 'cms_post_tag', 'tag_id', 'post_id');
    }
}
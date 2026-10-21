<?php
namespace Models; 

use Illuminate\Database\Eloquent\Relations\Pivot;

class CmsPostTag extends Pivot
{
    protected $table = 'cms_post_tag';
    public $timestamps = false;
    protected $guarded = [];
}
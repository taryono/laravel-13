<?php

namespace Models; 

class CbtProctoring extends BasedModel
{ 
    protected $table = 'cbt_proctoring';
    protected $fillable = [
        'session_id', 'proctor_id', 'start_proctoring', 'end_proctoring',
        'notes', 'status', 'flag_reason'
    ];
    protected $casts = ['start_proctoring' => 'datetime', 'end_proctoring' => 'datetime'];

    public function session(): BelongsTo { return $this->belongsTo(CbtSession::class, 'session_id'); }
    public function proctor(): BelongsTo { return $this->belongsTo(User::class, 'proctor_id'); }
}
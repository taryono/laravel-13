<?php
namespace Models; 

class RkasActivity extends BasedModel
{
    // Relasi
        public function rkasProgram()
    {
        return $this->belongsTo(RkasProgram::class, 'rkas_program_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
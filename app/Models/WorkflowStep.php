<?php
namespace Models;

class WorkflowStep extends SoftDeletableModel
{
    // Relasi
    public function workflowDefinition()
    {
        return $this->belongsTo(WorkflowDefinition::class, 'workflow_definition_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
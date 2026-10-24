<?php
namespace Models; 

class WorkflowApprover extends BasedModel
{
    // Relasi
        public function workflowStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'workflow_step_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
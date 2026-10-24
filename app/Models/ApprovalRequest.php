<?php
namespace Models; 

class ApprovalRequest extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function workflowDefinition()
    {
        return $this->belongsTo(WorkflowDefinition::class, 'workflow_definition_id');
    }

    public function currentStep()
    {
        return $this->belongsTo(CurrentStep::class, 'current_step_id');
    }

    public function requestable()
    {
        return $this->belongsTo(Requestable::class, 'requestable_id');
    }

    public function requester()
    {
        return $this->belongsTo(Requester::class, 'requester_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
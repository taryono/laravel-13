<?php
namespace Models; 

class ApprovalHistory extends BasedModel
{
    // Relasi
        public function approvalRequest()
    {
        return $this->belongsTo(ApprovalRequest::class, 'approval_request_id');
    }

    public function workflowStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'workflow_step_id');
    }

    public function approver()
    {
        return $this->belongsTo(Approver::class, 'approver_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
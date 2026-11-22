<?php
namespace Models;

class DigitalSignature extends SoftDeletableModel
{
    // Relasi
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function documentVersion()
    {
        return $this->belongsTo(DocumentVersion::class, 'document_version_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function signerUser()
    {
        return $this->belongsTo(SignerUser::class, 'signer_user_id');
    }

    public function signerEmployee()
    {
        return $this->belongsTo(SignerEmployee::class, 'signer_employee_id');
    }

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
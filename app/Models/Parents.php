<?php

namespace Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Parents extends SoftDeletableModel
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'user_id',
        'name',
        'nik',
        'gender',
        'birth_place',
        'birth_date',
        'religion',
        'nationality',
        'marital_status',
        'phone',
        'whatsapp',
        'home_phone',
        'email',
        'address',
        'address_detail',
        'rt',
        'rw',
        'village',
        'district',
        'city',
        'province',
        'postal_code',
        'education_level',
        'occupation',
        'occupation_status',
        'income_range',
        'workplace',
        'position',
        'special_needs',
        'is_alive',
        'death_date',
        'is_active',
        'family_card_number',
        'id_card_path',
        'family_card_path',
        'photo_path',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'death_date' => 'date',
            'is_alive' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'parent_student',
            'parent_id',
            'student_id'
        )
        ->withPivot([
            'organization_id',
            'relation',
            'relation_detail',
            'is_biological',
            'is_primary_contact',
            'is_emergency_contact',
            'emergency_contact_priority',
            'lives_with_student',
            'is_active',
        ])
        ->withTimestamps();
    }
}

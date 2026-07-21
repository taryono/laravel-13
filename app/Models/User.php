<?php
namespace App\Models;

use App\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens; 
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $guarded   = ['id'];
     protected $casts = [
        'deleted_at' => 'datetime',
    ];
    public $timestamps = true;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function adminlte_profile_url()
    {    
        return url("profile/" . $this->id);
    }

    public function adminlte_image()
    {

        if ($this->avatar && is_sftp_exists()) {
            if (! Storage::exists($this->avatar)) {
                if (! is_null($this->sex)) {
                    return $this->sex == "L" ? asset('assets/images/avatar-L.png') : asset('assets/images/avatar-P.png');
                }
            }
            return url("/partition/avatar?mode=preview&path=" . enkrip($this->avatar));
        }
        if (! is_null($this->sex)) {
            return $this->sex == "L" ? asset('assets/images/avatar-L.png') : asset('assets/images/avatar-P.png');
        }
        return asset('assets/icons/lsp.png');
    }

    public function adminlte_desc()
    {
        return $this->name;
    }

    public function isAdmin()
    {
        if ($this->hasRole('admin')) {
            return true;
        }
        return false;
    }

    public function isSuperUser()
    {
        return $this->hasRole('superuser');
    }

    public function isAsesi()
    {
        return $this->hasRole('asesi');
    }

    public function isAsesor()
    {
        return $this->hasRole('asesor');
    }

    public function isTimTeknis()
    {
        return $this->hasRole('teknis');
    }

    public function isPeninjau()
    {
        return $this->hasRole('peninjau');
    }

    public function isSupervisor()
    {
        if ($this->hasRole('supervisor')) {
            return true;
        }
        return false;
    }

    public function isValidator()
    {
        if ($this->hasRole('validator')) {
            return true;
        }
        return false;
    }

    public function roles()
    {
        return $this->belongsToMany(\Models\Role::class);
    }

    /**
     * @param string|array $roles
     */
    public function authorizeRoles($roles)
    {

        if ($this->isSuperUser()) {
            return true;
        }
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) || abort(401, 'This action is unauthorized.');
        }
        return $this->hasRole($roles) || abort(401, 'This action is unauthorized.');
    }

    /**
     * Check multiple roles
     * @param array $roles
     */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
     * Check one role
     * @param string $role
     */
    public function hasAnyRoleId($role)
    {
        return null !== $this->roles()->whereIn('id', $role)->first();
    }

    /**
     * Check one role
     * @param string $role
     */
    public function hasRoleId($role)
    {
        return null !== $this->roles()->where('role_user.role_id', $role)->first();
    }

    /**
     * Check one role
     * @param string $role
     */
    public function hasRole($role)
    {
        return Cache::remember($this->id . $role, 60 * 60, function () use ($role) {
            return null !== $this->roles()->where('name', $role)->first();
        });

    }

    public function hasAction($action)
    {
        return $this->action_user()->whereHas('action', function ($query) use ($action) {$query->where('name', $action);})->where("is_allowed", 1)->count() > 0;
    }

    public function isMale()
    {
        return $this->sex == "L";
    }

    public function isFemale()
    {
        return $this->sex == "P";
    }

    public function gender()
    {
        return $this->sex == "P" ? "Perempuan" : "Laki - laki";
    }

    public function action_user()
    {
        return $this->hasMany(\Models\ActionUser::class);
    }    
    public function whatsapp()
    {
        return $this->cellphone;
    }

    public function getSignature()
    {
        if (is_null($this->signature) || empty($this->signature)) {
            return null;
        }
        return url('/partition?path=' . enkrip($this->signature));
    }

    public function isSignatureExists()
    {
        if (is_null($this->signature) || empty($this->signature)) {
            return false;
        }
        try {
            if (count(config('global')) > 0) {
                if (array_key_exists('is_upload_to_sftp', config('global'))) {
                    if (is_null(config('global')['is_upload_to_sftp'])) {
                        $local = Storage::disk("local");
                        return $local->exists($this->signature);
                    } else {
                        return Storage::exists($this->signature);
                    }
                }
            } else {
                return Storage::exists($this->signature);
            }
        } catch (\Throwable $e) {
            return false;
        }
    }
 
    public function getImage($width = 100, $height = 100)
    {
        try { 
            return '<img src="' . public_path('assets/images/no_image.jpg') . '" width="' . $width . '" height="' . $height . '"/> ';

        } catch (\Throwable $th) {

        }
        return null;
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value,
            set: fn (string $value) => strtolower(trim($value)),
        );
    }

    public function setAuthSession($role, $roles)
    {
        session()->put('user_auth', [
            'login_as'      => $role->name,
            'auth_role_id'  => $role->id,
            'auth_role_ids' => $roles->pluck('id')->toArray(),
            'auth_roles'    => $roles->pluck('name')->toArray(),
            'auth_role'     => $role,
            'layout'        => $role->layout,
            'origin_role'   => $role,
        ]);
        session()->put('role_ids', $roles->pluck('id')->toArray());
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
 
}

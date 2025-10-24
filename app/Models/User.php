<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use App\Models\Role;
use App\Models\Permission;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'is_super_admin',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
    
    /**
     * Check if the user is a super admin.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin;
    }
    
    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
    
    /**
     * The permissions that belong to the user.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
    
    /**
     * Check if the user has a specific role.
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        return $this->roles()->where('name', $role)->exists();
    }
    
    /**
     * Check if the user has a specific permission.
     *
     * @param  string  $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        return $this->permissions()->where('name', $permission)->exists() || 
               $this->roles()->whereHas('permissions', function ($query) use ($permission) {
                   $query->where('name', $permission);
               })->exists();
    }
}

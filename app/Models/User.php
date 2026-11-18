<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'pegawai_id',
        'is_active',
        'last_login_at',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Override role attribute to always return the Role model
     */
    public function getRoleAttribute()
    {
        if ($this->relationLoaded('role')) {
            $rel = $this->getRelation('role');
            if ($rel instanceof Role) {
                return $rel;
            }
        }
        if ($this->role_id) {
            $roleModel = Role::find($this->role_id);
            if ($roleModel) {
                $this->setRelation('role', $roleModel);
                return $roleModel;
            }
        }
        return null;
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    /**
     * Check if user is an Administrator
     */
    public function isAdministrator(): bool
    {
        if ($this->role_id === 1) {
            return true;
        }
        if ($this->role && strtolower(trim($this->role->name)) === 'administrator') {
            return true;
        }
        if (strtolower(trim($this->username ?? '')) === 'admin') {
            return true;
        }
        return false;
    }

    /**
     * Check if user has a specific role name (or array of names)
     */
    public function hasRole(string|array $roles): bool
    {
        if ($this->isAdministrator()) {
            return true;
        }
        if (!$this->role) {
            return false;
        }

        $roles = (array) $roles;
        $currentRoleName = strtolower(trim($this->role->name));

        foreach ($roles as $r) {
            if (strtolower(trim($r)) === $currentRoleName) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has permission to access a module (or array of module keys)
     */
    public function hasPermission(string|array $permissions): bool
    {
        // Administrator has unrestricted access to all modules
        if ($this->isAdministrator()) {
            return true;
        }

        if (!$this->role || empty($this->role->permissions)) {
            return false;
        }

        $rolePermissions = (array) $this->role->permissions;
        $permissions = (array) $permissions;

        foreach ($permissions as $p) {
            if (in_array($p, $rolePermissions, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get user display role name
     */
    public function getRoleNameAttribute(): string
    {
        if ($this->isAdministrator()) {
            return 'Administrator';
        }
        return $this->role ? $this->role->name : 'Staff';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
        ];
    }
}

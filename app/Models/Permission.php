<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'category',
        'is_system'
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    /**
     * Roles that have this permission
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }

    /**
     * Check if permission is granted to a role
     */
    public function isGrantedTo(Role $role)
    {
        return $this->roles->contains('id', $role->id);
    }

    /**
     * Scope to get permissions by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get system permissions
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Scope to get custom permissions
     */
    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }
}
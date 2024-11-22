<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'description',
    'login_web',
    'login_app',
    'created_at',
    'updated_at',
  ];

  public function getLoginWebStatusAttribute()
  {
    return $this->login_web ? __('Enabled') : __('Disabled');
  }

  public function getLoginAppStatusAttribute()
  {
    return $this->login_app ? __('Enabled') : __('Disabled');
  }

  public function user()
  {
    return $this->belongsToMany(User::class);
  }

  public function permiPermissions()
  {
    return $this->belongsToMany(PermiPermission::class, 'permi_permission_role');
  }

  public function permiPermissionRole()
  {
    return $this->hasMany(PermiPermissionRole::class);
  }
}

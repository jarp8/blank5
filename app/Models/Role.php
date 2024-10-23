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
  ];

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermiPermissionRole extends Model
{
  use HasFactory;

  protected $table = 'permi_permission_role';

  protected $fillable = [
    'permi_permission_id',
    'role_id'
  ];

  public function permiPermissions()
  {
    return $this->belongsTo(PermiPermission::class, 'permi_permission_id', 'id');
  }
}

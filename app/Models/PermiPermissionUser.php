<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermiPermissionUser extends Model
{
  use HasFactory;

  protected $table = 'permi_permission_user';

  protected $fillable = [
    'permi_permission_id',
    'user_id'
  ];

  public function permiPermissions()
  {
    return $this->belongsTo(PermiPermission::class, 'permi_permission_id', 'id');
  }
}

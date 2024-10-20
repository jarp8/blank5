<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermiPermissionRole extends Model
{
  use HasFactory;

  protected $fillable = [
    'permi_permission_id',
    'role_id'
  ];
}

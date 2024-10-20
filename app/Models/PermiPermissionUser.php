<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermiPermissionUser extends Model
{
  use HasFactory;

  protected $fillable = [
    'permi_permission_id',
    'user_id'
  ];
}

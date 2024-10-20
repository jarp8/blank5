<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermiFunction extends Model
{
  use HasFactory;

  public static $resourceControllerFunctions = [
    'index',
    'create',
    'store',
    'show',
    'edit',
    'update',
    'destroy'
  ];

  protected $fillable = [
    'name',
    'description'
  ];

  public function permiPermissions()
  {
    return $this->hasMany(PermiPermission::class);
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermiModule extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'is_active',
    'permi_module_id',
    'permi_module_type_id',
  ];

  public function permiPermissions()
  {
    return $this->hasMany(PermiPermission::class);
  }

  public function subModules()
  {
    return $this->hasMany(PermiModule::class)->with('permiPermissions.permiFunction');
  }

  public function allSubModules()
  {
    return $this->subModules()->with('allSubModules');
  }
}

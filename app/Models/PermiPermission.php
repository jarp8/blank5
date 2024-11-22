<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermiPermission extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'permi_module_id',
    'permi_function_id',
  ];

  public function users()
  {
    return $this->belongsToMany(User::class);
  }

  public function roles()
  {
    return $this->belongsToMany(Role::class);
  }

  public function permiFunction()
  {
    return $this->belongsTo(PermiFunction::class);
  }

  public function permiModule()
  {
    return $this->belongsTo(PermiModule::class);
  }
}

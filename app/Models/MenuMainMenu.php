<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuMainMenu extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'icon',
    'is_visible',
    'menu_main_menu_id',
    'permi_permission_id'
  ];

  public function scopeVisible(Builder $query)
  {
    $query->where('is_visible', 1);
  }

  public function permiPermissions()
  {
    return $this->belongsTo(PermiPermission::class, 'permi_permission_id')->with('permiFunction');
  }

  public function subMenus()
  {
    return $this->hasMany(MenuMainMenu::class)->with('permiPermissions');
  }

  public function activeSubmenus()
  {
    return $this->hasMany(MenuMainMenu::class)->with('permiPermissions')->where('is_visible', 1);
  }

  public function allsubMenus()
  {
    return $this->subMenus()->with('allsubMenus');
  }

  public function allActiveSubmenus()
  {
    return $this->activeSubmenus()->with('allActiveSubmenus');
  }
}

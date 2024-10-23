<?php

namespace App\Permissions;

class StructureItem extends MainStructure
{
  public $id;
  public $name;
  public $icon;
  public $permi_module_type_id;
  public $sub_modules;
  public $is_visible;
  public $is_active;

  public function __construct($attrs)
  {
    $this->id = $attrs['id'] ?? null;
    $this->name = $attrs['name'];
    $this->icon = $attrs['icon'] ?? null;
    $this->permi_module_type_id = $attrs['permi_module_type_id'];
    $this->sub_modules = $this->castModules($attrs['sub_modules'] ?? null);
    $this->is_visible = $attrs['is_visible'] ?? 1;
    $this->is_active = $attrs['is_active'] ?? 1;
  }
}

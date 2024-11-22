<?php

namespace App\Permissions;

use App\Permissions\Enums\ModuleType;

class StructureItemModule extends StructureItem
{
  public function __construct($attrs)
  {
    $attrs['permi_module_type_id'] = ModuleType::module->value;
    parent::__construct($attrs);
  }

  public function addSubModule(StructureItem $item)
  {
    array_push($this->subModules, $item);
  }
}

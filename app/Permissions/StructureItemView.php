<?php

namespace App\Permissions;

use App\Permissions\Enums\ModuleType;

class StructureItemView extends StructureItem
{
  public function __construct($attrs)
  {
    $attrs['permi_module_type_id'] = ModuleType::view->value;
    $attrs['sub_modules'] = null;
    parent::__construct($attrs);
  }
}

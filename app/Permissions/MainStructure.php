<?php

namespace App\Permissions;

class MainStructure
{
  public $structure;

  public function __construct($structure = [])
  {
    $this->structure = $structure;
  }

  public function castStructure()
  {
    return $this->castModules($this->structure);
  }

  public function castModules($modules)
  {
    if (!$modules) return null;

    $castSubModules = [];

    foreach ($modules as $module) {
      if (isset($module['sub_modules'])) {
        $castSubModules[] = new StructureItemModule($module + [
          'sub_modules' => $this->castModules($module['sub_modules'] ?? null)
        ]);
      } else {
        $castSubModules[] = new StructureItemView($module);
      }
    }

    return $castSubModules;
  }

  public function getStructureIds()
  {
    $structure = $this->castModules($this->structure);

    $ids = $this->getArrayAttributeRecursively($structure, 'id');

    return $ids;
  }

  public function filterStructure($callback)
  {
    $this->structure = $this->filterRecursive(
      $this->structure,
      function ($item) use ($callback) {
        return $callback($item);
      }
    );

    return $this;
  }

  private function filterRecursive($modules, $callback)
  {
    $filtered = [];

    foreach ($modules as $module) {

      if (!$callback($module)) {
        continue;
      }

      if (isset($module['sub_modules'])) {
        $module['sub_modules'] = $this->filterRecursive($module['sub_modules'], $callback);

        if (!empty($module['sub_modules']) || $callback($module)) {
          $filtered[] = $module;
        }
      } else {
        if ($callback($module)) {
          $filtered[] = $module;
        }
      }
    }

    return $filtered;
  }

  private function getArrayAttributeRecursively($modules, $attribute)
  {
    $attrs = [];

    foreach ($modules as $module) {
      if (isset($module->$attribute)) {
        $attrs[] = $module->$attribute;
      }

      if (isset($module->sub_modules)) {
        $attrs = array_merge($attrs, $this->getArrayAttributeRecursively(
          $module->sub_modules,
          $attribute
        ));
      }
    }

    return $attrs;
  }
}

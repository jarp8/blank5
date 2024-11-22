<?php

namespace App\Permissions;

use App\Models\MenuMainMenu as ModelsMenuMainMenu;
use App\Models\PermiPermission;
use App\Permissions\Enums\ModuleType;

class MenuMainMenu
{
  public $structure; //Estructura del menú

  public function __construct($structure)
  {
    $this->structure = $structure;
  }

  //Insertar el menú con su respectiva estructura de manera recursiva
  //$structure = Estructura del menú hijo
  //$mainMenuId = Si existe un menú hijo asignarle el id del padre
  public function insertMenu($structure = [], $mainMenuId = null, $parentMenu = null)
  {
    //Si hay una estructura hija asignarlo a $localStructure, caso contrario obtener la
    //estructura de la variable global
    $localStructure = (count($structure) > 0)
      ? $structure
      : $this->structure;

    foreach ($localStructure as $menuItem) {
      $menu = ModelsMenuMainMenu::updateOrCreate([
        'name' => $menuItem->name,
        'menu_main_menu_id' => $mainMenuId,
        'permi_permission_id' => ($menuItem->permi_module_type_id == ModuleType::module->value)
          ? null
          : PermiPermission::where('name', "{$menuItem->name}.index")->first()->id
      ], [
        'name' => $menuItem->name,
        'icon' => $menuItem->icon ?? null,
        'is_visible' => $menuItem->is_active == false
          ? $menuItem->is_active
          : ($parentMenu != null && $parentMenu->is_visible == false
            ? $parentMenu->is_visible
            : $menuItem->is_visible),
      ]);

      //En caso de que tenga submódulos volver a llamar la función insertMenu
      if ($menuItem->sub_modules ?? false) {
        $this->insertMenu($menuItem->sub_modules, $menu->id, $menu);
      }
    }
  }
}

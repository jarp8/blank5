<?php

namespace App\Permissions\Traits;

use App\Models\MenuMainMenu;

trait HasMenu
{
  public function authMenu()
  {
    $menuMain = MenuMainMenu::visible()
      ->with('allActiveSubmenus')
      ->where('menu_main_menu_id', null)
      ->get()
      ->toArray();

    $allPermissions = $this->allPermissions();

    $structure = self::getStructure($menuMain, $allPermissions);

    return $structure;
  }

  private static function getStructure($structure, $permissions)
  {
    // Inicializamos la variable $result = [] para cuando vuelva a entrar en recursión
    // tenga sus propios valores y no exista un conflicto
    $result = [];

    foreach ($structure as $menuMain) {
      // Si no tiene submenú quiere decir que es un único elemento
      if (empty($menuMain['all_active_submenus'])) {
        if ($permissions->contains('permi_permission_id', $menuMain['permi_permission_id'] ?? null)) {
          $result[] = [
            'url'   => strtolower($menuMain['name']),
            'name'  => $menuMain['name'],
            'icon'  => $menuMain['icon'],
            'slug'  => strtolower($menuMain['name']) . '.index',
          ];
        }
      } else {
        // Si tiene submenú realizamos la recursión
        $submenu = self::getStructure($menuMain['all_active_submenus'], $permissions);

        if (!empty($submenu)) {
          $result[] = [
            'name'    => $menuMain['name'],
            'icon'    => $menuMain['icon'],
            'slug'    => null,
            'submenu' => $submenu,
          ];
        }
      }
    }

    return $result;
  }
}

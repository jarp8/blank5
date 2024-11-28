<?php

namespace Database\Seeders;

use App\Permissions\MainStructure;
use App\Permissions\MenuMainMenu;
use App\Permissions\PermissionModule;
use App\Permissions\PermissionPermission;
use App\Permissions\StructureItemModule;
use App\Permissions\StructureItemView;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermiModuleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->command->info('Creating modules, permissions and menu');

    // Materialize Theme:
    // https://demos.pixinvent.com/materialize-html-laravel-admin-template/demo-1/

    // Icons:
    // https://remixicon.com/

    // Dashboard
    $dashboard = new StructureItemView(['name' => 'admin.dashboard', 'icon' => 'menu-icon tf-icons ri-home-smile-line']);

    // Users
    $users = new StructureItemModule([
      'name' => 'admin.users',
      'icon' => 'menu-icon tf-icons ri-group-line',
      'sub_modules' => [
        ['name' => 'admin.roles'],
        ['name' => 'admin.users'],
      ]
    ]);

    // Main structure
    $structure = new MainStructure([
      $dashboard,
      $users,
    ]);

    // Modules
    $modules = new PermissionModule($structure);
    $modules->insertModules();

    // Permissions
    $permissions = new PermissionPermission($modules->insertedViewsStructure->castStructure());
    $permissions->insertControllerPermissions();

    // Menu
    $menu = new MenuMainMenu($modules->insertedStructure->castStructure());
    $menu->insertMenu();

    // Mensaje de éxito
    $this->command->info('Modules, permissions and menu created successfully');
  }
}

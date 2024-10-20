<?php

namespace Database\Seeders;

use App\Permissions\MainStructure;
use App\Permissions\PermissionModule;
use App\Permissions\PermissionPermission;
// use App\Permissions\StructureItemModule;
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

    // Home
    $home = new StructureItemView(['name' => 'home', 'icon' => '<i class="fa-solid fa-house"></i>']);

    // Users
    // $users = new StructureItemModule([
    //     'name' => 'Users', 'icon' => '<i class="fa-solid fa-users"></i>', 'sub_modules' => [
    //         ['name' => 'roles', 'icon' => '<i class="fa-solid fa-user-tie"></i>'],
    //         ['name' => 'users', 'icon' => '<i class="fa-solid fa-user"></i>'],
    //     ]
    // ]);

    // Main structure
    $structure = new MainStructure([
      $home,
      // $users,
    ]);

    // Modules
    $modules = new PermissionModule($structure);
    $modules->insertModules();

    // Permissions
    $permissions = new PermissionPermission($modules->insertedViewsStructure->castStructure());
    $permissions->insertControllerPermissions();
  }
}

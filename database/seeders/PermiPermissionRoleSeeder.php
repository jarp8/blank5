<?php

namespace Database\Seeders;

use App\Models\PermiPermission;
use App\Models\PermiPermissionRole;
use App\Permissions\Enums\RoleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermiPermissionRoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $permissions = PermiPermission::all();

    foreach ($permissions as $permission) {
      PermiPermissionRole::firstOrCreate([
        'permi_permission_id' => $permission->id,
        'role_id' => RoleType::admin->value
      ]);
    }
  }
}

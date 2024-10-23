<?php

namespace Database\Seeders;

use App\Models\PermiPermissionUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermiPermissionsPermiUserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    PermiPermissionUser::create([
      'permi_permission_id' => 1,
      'user_id' => 1
    ]);
  }
}

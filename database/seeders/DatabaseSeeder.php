<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    User::firstOrCreate([
      'name' => 'User',
      'email' => 'user@example.com',
      'password' => Hash::make('secret'),
    ]);

    // User::factory(100)->create();

    $this->call([
      RoleSeeder::class,
      RoleUserSeeder::class,

      PermiModuleTypeSeeder::class,
      PermiFunctionSeeder::class,
      PermiModuleSeeder::class,

      PermiPermissionRoleSeeder::class,
      PermiPermissionsPermiUserSeeder::class,
    ]);
  }
}

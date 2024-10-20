<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Role::firstOrCreate([
      'name' => 'Admin',
      'description' => null,
      'login_web' => 1,
      'login_app' => 0,
    ]);

    Role::firstOrCreate([
      'name' => 'General user',
      'description' => null,
      'login_web' => 1,
      'login_app' => 0,
    ]);
  }
}

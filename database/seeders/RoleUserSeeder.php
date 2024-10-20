<?php

namespace Database\Seeders;

use App\Models\RoleUser;
use App\Permissions\Enums\RoleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    RoleUser::firstOrCreate([
      'role_id' => RoleType::admin->value,
      'user_id' => 1,
    ]);
  }
}

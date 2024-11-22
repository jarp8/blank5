<?php

namespace Database\Seeders;

use App\Models\PermiModuleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermiModuleTypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    PermiModuleType::firstOrCreate([
      'name' => 'module',
    ]);

    PermiModuleType::firstOrCreate([
      'name' => 'view',
    ]);
  }
}

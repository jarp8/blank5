<?php

namespace Database\Seeders;

use App\Models\PermiFunction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermiFunctionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    foreach (PermiFunction::$resourceControllerFunctions as $functionName) {
      PermiFunction::firstOrCreate(['name' => $functionName]);
    }
  }
}

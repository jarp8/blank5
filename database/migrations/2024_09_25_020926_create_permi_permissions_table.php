<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('permi_permissions', function (Blueprint $table) {
      $table->id();
      $table->string('name')->comment('Nombre de la relación');
      $table->foreignId('permi_module_id')->comment('Llave foránea a permi_modules')->constrained();
      $table->foreignId('permi_function_id')->comment('Llave foránea a permi_functions')->constrained();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('permi_permissions');
  }
};

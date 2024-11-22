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
    Schema::create('permi_modules', function (Blueprint $table) {
      $table->id();
      $table->string('name')->comment('Nombre del módulo');
      $table->boolean('is_active')->default(1)->comment('Si es 1 esta activo, caso contrario es 0');
      $table->foreignId('permi_module_id')->nullable()->comment('Referencia a la misma tabla')->constrained();
      $table->foreignId('permi_module_type_id')->comment('Llave foránea a permi_module_types')->constrained();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('permi_modules');
  }
};

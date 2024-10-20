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
    Schema::create('permi_functions', function (Blueprint $table) {
      $table->id();
      $table->string('name')->comment('Nombre del permiso');
      $table->string('description')->nullable()->comment('Descripción del permiso');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('permi_functions');
  }
};

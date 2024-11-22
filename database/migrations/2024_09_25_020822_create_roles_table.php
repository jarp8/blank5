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
    Schema::create('roles', function (Blueprint $table) {
      $table->id();
      $table->string('name')->comment('Nombre del rol');
      $table->string('description')->nullable()->comment('Descripción del rol');
      $table->boolean('login_web')->default(0)->comment('Puede iniciar sesión en web');
      $table->boolean('login_app')->default(0)->comment('Puede iniciar sesión en la app móvil');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('roles');
  }
};

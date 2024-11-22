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
    Schema::create('menu_main_menus', function (Blueprint $table) {
      $table->id();
      $table->string('name')->comment('Nombre del menú');
      $table->string('icon')->nullable()->comment('Icono del menú');
      $table->boolean('is_visible')->default(1)->comment('Si es 1 esta visible en el sidebar, caso contrario es 0');
      $table->foreignId('menu_main_menu_id')->nullable()->comment('Referencia a la misma tabla')->constrained();
      $table->foreignId('permi_permission_id')->nullable()->comment('Llave foránea a permi_permissions')->constrained();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('menu_main_menus');
  }
};

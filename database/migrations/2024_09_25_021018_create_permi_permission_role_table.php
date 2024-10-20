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
    Schema::create('permi_permission_role', function (Blueprint $table) {
      $table->id();
      $table->foreignId('permi_permission_id')->comment('Llave foránea de permi_permissions')->constrained();
      $table->foreignId('role_id')->comment('Llave foránea de roles')->constrained();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('permi_permission_role');
  }
};

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
    Schema::create('role_user', function (Blueprint $table) {
      $table->id();
      $table->foreignId('role_id')->comment('Llave foránea de roles')->constrained();
      $table->foreignId('user_id')->comment('Llave foránea de users')->constrained();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('role_user');
  }
};

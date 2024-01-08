<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('naive_bayes', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('hero_id');
      $table->unsignedBigInteger('hero_musuh_id');
      $table->enum('tipe_build', ['Physical', 'Magic', 'Tank']);
      $table->enum('hasil', ['Menang', 'Kalah']);

      $table->foreign('hero_id')->references('id')->on('heroes');
      $table->foreign('hero_musuh_id')->references('id')->on('heroes');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('naive_bayes');
  }
};

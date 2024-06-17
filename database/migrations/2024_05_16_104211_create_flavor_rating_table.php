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
        Schema::create('flavor_rating', function (Blueprint $table) {
            $table->foreignId('flavor_id')->constrained('flavors');
            $table->foreignId('rating_id')->constrained('ratings');
            $table->unsignedInteger('rating');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flavor_rating');
    }
};

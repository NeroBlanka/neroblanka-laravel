<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('freelance_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->integer('hourly_rate')->nullable(); // DA/h
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->unsignedInteger('completed_count')->default(0);
            $table->json('service_types')->nullable(); // array of ServiceType values
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freelance_profiles');
    }
};

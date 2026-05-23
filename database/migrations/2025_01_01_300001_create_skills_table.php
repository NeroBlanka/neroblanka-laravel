<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('category')->nullable(); // ex: "3d", "motion", "web", "ia"
        });

        Schema::create('freelance_skills', function (Blueprint $table) {
            $table->uuid('freelance_id');
            $table->foreign('freelance_id')->references('id')->on('users')->cascadeOnDelete();
            $table->uuid('skill_id');
            $table->foreign('skill_id')->references('id')->on('skills')->cascadeOnDelete();
            $table->primary(['freelance_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freelance_skills');
        Schema::dropIfExists('skills');
    }
};

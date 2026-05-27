<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
            $table->uuid('freelance_id');
            $table->foreign('freelance_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestampTz('assigned_at')->useCurrent();
            $table->string('status')->default('active');
            $table->text('internal_notes')->nullable();
            $table->unique(['project_id', 'freelance_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};

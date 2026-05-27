<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('client_id');
            $table->foreign('client_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('service_type')->nullable();
            $table->string('status')->default('pending');
            $table->integer('budget_da')->nullable();
            $table->date('deadline')->nullable();
            $table->text('brief_file_url')->nullable();
            $table->text('reference_urls')->nullable();
            $table->text('notes')->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

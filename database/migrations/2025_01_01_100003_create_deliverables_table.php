<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliverables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assignment_id');
            $table->foreign('assignment_id')->references('id')->on('assignments')->cascadeOnDelete();
            $table->text('file_url');
            $table->string('file_name');
            $table->text('message')->nullable();
            $table->timestampTz('submitted_at')->useCurrent();
            $table->timestampTz('approved_at')->nullable();
            $table->text('revision_notes')->nullable();
            $table->integer('version')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliverables');
    }
};

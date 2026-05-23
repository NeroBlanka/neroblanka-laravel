<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('briefs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lead_id');
            $table->foreign('lead_id')->references('id')->on('leads')->cascadeOnDelete();
            $table->json('answers'); // réponses du wizard par étape
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('briefs');
    }
};

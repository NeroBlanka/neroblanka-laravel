<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funnel_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lead_id')->nullable();
            $table->foreign('lead_id')->references('id')->on('leads')->nullOnDelete();
            $table->string('event'); // brief_started, step_completed, brief_submitted, page_viewed
            $table->string('session_id')->nullable();
            $table->json('metadata')->nullable();
            $table->json('utm')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funnel_events');
    }
};

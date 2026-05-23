<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('service_type');
            $table->string('status')->default('new');
            $table->integer('score')->default(0);
            $table->string('budget_range')->nullable();
            $table->string('deadline_range')->nullable();
            $table->string('client_type')->nullable(); // startup, pme, event, export
            $table->string('source')->nullable(); // organic, referral, social, direct
            $table->jsonb('utm')->nullable();
            $table->jsonb('raw_payload')->nullable();
            $table->jsonb('social_links')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};

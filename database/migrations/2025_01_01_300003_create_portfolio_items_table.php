<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client_name');
            $table->string('service_type');
            $table->text('excerpt');
            $table->text('content')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('gallery')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('featured')->default(false);
            $table->date('published_at')->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
    }
};

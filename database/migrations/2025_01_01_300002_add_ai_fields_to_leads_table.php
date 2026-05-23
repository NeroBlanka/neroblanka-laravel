<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->text('ai_summary')->nullable()->after('score');
            $table->integer('ai_score_adjustment')->default(0)->after('ai_summary');
            $table->timestampTz('ai_analyzed_at')->nullable()->after('ai_score_adjustment');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['ai_summary', 'ai_score_adjustment', 'ai_analyzed_at']);
        });
    }
};

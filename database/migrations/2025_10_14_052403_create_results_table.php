<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(table: 'results', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'alternative_id')->constrained()->onDelete(action: 'cascade');
            $table->float(column: 'preference_score');
            $table->integer(column: 'rank');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table:'results');
    }
};
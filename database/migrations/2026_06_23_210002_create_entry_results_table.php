<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('entry_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained('entries')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('result_category_id')->constrained('result_categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('position')->nullable();
            $table->string('gap')->nullable();
            $table->timestamps();

            $table->unique(['entry_id', 'result_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_results');
    }
};

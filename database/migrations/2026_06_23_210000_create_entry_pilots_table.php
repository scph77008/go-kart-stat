<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('entry_pilots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained('entries')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('pilot_id')->constrained('pilots')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('role')->nullable();
            $table->timestamps();

            $table->unique(['entry_id', 'pilot_id']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('entry_pilots');
    }
};

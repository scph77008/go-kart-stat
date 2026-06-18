<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('participants')
                ->default(0)
                ->comment('Участники');
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->boolean('position')
                ->default(0)
                ->comment('Позиция');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('participants');
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};

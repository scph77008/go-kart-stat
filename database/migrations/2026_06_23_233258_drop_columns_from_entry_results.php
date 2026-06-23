<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('entry_results', 'participants_count')) {
            Schema::table('entry_results', function (Blueprint $table) {
                $table->dropColumn('participants_count');
            });
        }

        if (Schema::hasColumn('entry_results', 'points')) {
            Schema::table('entry_results', function (Blueprint $table) {
                $table->dropColumn('points');
            });
        }
    }

    public function down(): void
    {

    }
};

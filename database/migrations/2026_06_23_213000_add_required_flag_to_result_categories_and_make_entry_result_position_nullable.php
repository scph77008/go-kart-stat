<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('result_categories', 'is_required')) {
            Schema::table('result_categories', function (Blueprint $table) {
                $table->boolean('is_required')->default(false)->after('participants');
            });
        }

        DB::table('result_categories')
            ->where('name', 'Абсолют')
            ->update(['is_required' => true]);

        Schema::table('entry_results', function (Blueprint $table) {
            $table->unsignedInteger('position')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('entry_results', function (Blueprint $table) {
            $table->unsignedInteger('position')->nullable(false)->change();
        });

        Schema::table('result_categories', function (Blueprint $table) {
            $table->dropColumn('is_required');
        });
    }
};

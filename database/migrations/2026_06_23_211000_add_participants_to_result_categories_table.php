<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('result_categories', 'participants')) {
            Schema::table('result_categories', function (Blueprint $table) {
                $table->unsignedInteger('participants')->default(0)->after('name');
            });
        }
    }

    public function down(): void
    {
        Schema::table('result_categories', function (Blueprint $table) {
            $table->dropColumn('participants');
        });
    }
};

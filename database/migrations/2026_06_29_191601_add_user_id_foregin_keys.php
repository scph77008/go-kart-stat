<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    private function tables(): array
    {
        return [
            'championships',
            'entries',
            'entry_pilots',
            'entry_results',
            'events',
            'pilots',
            'result_categories',
            'teams',
            'tracks',
        ];
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables() as $table) {
            Schema::table($table, static function (Blueprint $table) {
                $table->foreignId('created_by')
                    ->default(1)
                    ->constrained('users');

                $table->foreignId('updated_by')
                    ->nullable()
                    ->constrained('users');

                $table->foreignId('deleted_by')
                    ->nullable()
                    ->constrained('users');

                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables() as $table) {
            Schema::table($table, static function (Blueprint $table) {
                $table->dropColumn('created_by');
                $table->dropColumn('updated_by');
                $table->dropColumn('deleted_by');
                $table->dropSoftDeletes();
            });
        }
    }
};

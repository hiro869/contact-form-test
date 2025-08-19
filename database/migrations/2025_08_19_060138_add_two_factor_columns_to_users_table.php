<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'two_factor_secret')) {
                $table->text('two_factor_secret')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'two_factor_recovery_codes')) {
                $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            }
            //（必要なら）確認日時も追加
            if (!Schema::hasColumn('users', 'two_factor_confirmed_at')) {
                $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = array_filter([
                Schema::hasColumn('users','two_factor_secret') ? 'two_factor_secret' : null,
                Schema::hasColumn('users','two_factor_recovery_codes') ? 'two_factor_recovery_codes' : null,
                Schema::hasColumn('users','two_factor_confirmed_at') ? 'two_factor_confirmed_at' : null,
            ]);
            if ($cols) {
                $table->dropColumn($cols);
            }
        });
    }
};

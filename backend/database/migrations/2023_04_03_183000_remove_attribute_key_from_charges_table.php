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
        if (! Schema::hasColumn('charges', 'key')) {
            return;
        }

        Schema::table('charges', function (Blueprint $table) {
            $table->dropColumn('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('charges', 'key')) {
            return;
        }

        Schema::table('charges', function (Blueprint $table) {
            $table->string('key')->nullable();
        });
    }
};

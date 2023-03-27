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
        Schema::table('charges', function (Blueprint $table) {
            $table->dropForeign(['colocation_id']);
            $table->foreign('colocation_id')->references('id')->on('colocations')->onDelete('cascade');
        });

        Schema::table('colocation_charges_users', function (Blueprint $table) {
            $table->dropForeign(['charge_id']);
            $table->foreign('charge_id')->references('id')->on('charges')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['colocation_id']);
            $table->foreign('colocation_id')->references('id')->on('colocations')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('charges', function (Blueprint $table) {
            $table->dropForeign(['colocation_id']);
            $table->foreign('colocation_id')->references('id')->on('colocations');
        });

        Schema::table('colocation_charges_users', function (Blueprint $table) {
            $table->dropForeign(['charge_id']);
            $table->foreign('charge_id')->references('id')->on('charges');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['colocation_id']);
            $table->foreign('colocation_id')->references('id')->on('colocations');
        });
    }
};

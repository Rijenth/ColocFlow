<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('charges', function (Blueprint $table) {
            $table->unsignedInteger('amount_affected')->nullable();
        });

        $charges = DB::table('charges')->get();

        foreach ($charges as $charge) {
            $amountAffected = DB::table('colocation_charges_users')
                ->where('charge_id', $charge->id)
                ->sum('amount');

            DB::table('charges')
                ->where('id', $charge->id)
                ->update(['amount_affected' => $amountAffected]);
        }

        Schema::table('charges', function (Blueprint $table) {
            $table->unsignedInteger('amount_affected')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('charges', function (Blueprint $table) {
            $table->dropColumn('amount_affected');
        });
    }
};

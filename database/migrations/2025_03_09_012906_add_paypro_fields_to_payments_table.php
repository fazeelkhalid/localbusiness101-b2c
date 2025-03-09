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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('paypro_id')->nullable()->after('payment_id');
            $table->string('payment_link')->nullable()->after('payment_id');
            $table->string('payment_method')->nullable()->after('payment_link');
            $table->string('paypro_payment_status')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['paypro_id', 'payment_method', 'paypro_payment_status']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transporters', function (Blueprint $table) {
            $table->string('easypaisa_no', 20)->nullable()->after('address');
            $table->string('jazzcash_no', 20)->nullable()->after('easypaisa_no');
            $table->string('bank_name')->nullable()->after('jazzcash_no');
            $table->string('bank_account_title')->nullable()->after('bank_name');
            $table->string('bank_account_no', 50)->nullable()->after('bank_account_title');
        });
    }

    public function down(): void
    {
        Schema::table('transporters', function (Blueprint $table) {
            $table->dropColumn([
                'easypaisa_no',
                'jazzcash_no',
                'bank_name',
                'bank_account_title',
                'bank_account_no',
            ]);
        });
    }
};

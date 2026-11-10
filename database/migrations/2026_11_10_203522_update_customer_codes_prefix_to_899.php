<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing customers with prefix '260' to '899'
        $customers = DB::table('customers')->where('customer_code', 'like', '260%')->get();
        foreach ($customers as $c) {
            $newCode = '899' . substr($c->customer_code, 3);
            DB::table('customers')->where('id', $c->id)->update(['customer_code' => $newCode]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert prefix '899' back to '260'
        $customers = DB::table('customers')->where('customer_code', 'like', '899%')->get();
        foreach ($customers as $c) {
            $oldCode = '260' . substr($c->customer_code, 3);
            DB::table('customers')->where('id', $c->id)->update(['customer_code' => $oldCode]);
        }
    }
};

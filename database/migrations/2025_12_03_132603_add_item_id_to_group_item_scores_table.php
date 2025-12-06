<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('group_item_scores', function (Blueprint $table) {
        // Sesuaikan tipe data dengan id di tabel items (biasanya bigInteger/unsignedBigInteger)
        $table->unsignedBigInteger('item_id')->nullable()->after('group_item_id');
        
        // Opsional: Foreign key
        // $table->foreign('item_id')->references('item_id')->on('items');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_item_scores', function (Blueprint $table) {
            //
        });
    }
};

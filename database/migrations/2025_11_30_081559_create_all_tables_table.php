<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       
        // 2. Table: groups (Kategori Utama)
        Schema::create('groups', function (Blueprint $table) {
            $table->id('group_id'); // default: id
            $table->string('group_name');
            $table->float('group_rate')->nullable(); // Bobot B
            $table->timestamps();
        });

        // 3. Table: group_items (Sub-Kategori)
        Schema::create('group_items', function (Blueprint $table) {
            $table->id('group_item_id');
            $table->foreignId('group_id');
            $table->string('group_item_name');
            $table->float('group_item_rate')->nullable(); // Bobot D
            $table->timestamps();
        });

        // 4. Table: items (Pilihan Jawaban)
        Schema::create('items', function (Blueprint $table) {
            $table->id( 'item_id');
            $table->foreignId('group_item_id');
            $table->string('item_name');
            $table->float('item_rate')->nullable(); // Bobot F
            $table->timestamps();
        });

        // 5. Table: applications (Data Pemohon)
        Schema::create('applications', function (Blueprint $table) {
            $table->id('application_id');
            $table->string('application_number', 50)->nullable();
            $table->string('application_name');
            $table->string('application_birth_place', 100)->nullable();
            $table->date('application_birth_date')->nullable();
            $table->string('application_gender', 20)->nullable();
            $table->text('application_address')->nullable();
            $table->string('application_postal_code', 20)->nullable();
            $table->float('application_summary_score')->nullable();
            $table->timestamps();
        });

        // 6. Table: group_scores
        Schema::create('group_scores', function (Blueprint $table) {
            $table->id('group_score_id');
            $table->foreignId('application_id');
            $table->foreignId('group_id');
            $table->float('group_score')->nullable();
            $table->timestamps();
        });

        // 7. Table: group_item_scores
        Schema::create('group_item_scores', function (Blueprint $table) {
            $table->id('group_item_score_id');
            $table->foreignId('application_id');
            $table->foreignId('group_item_id');
            $table->float('group_item_score')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_item_scores');
        Schema::dropIfExists('group_scores');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('items');
        Schema::dropIfExists('group_items');
        Schema::dropIfExists('groups');
    }
};
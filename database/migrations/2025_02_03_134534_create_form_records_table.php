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
        Schema::create('form_records', function (Blueprint $table) {
            $table->id();
            $table->integer('form_id');
            $table->integer('info_id');
            $table->string('Counted')->nullable();
            $table->string('Wastage')->nullable();
            $table->string('Conflict')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_records');
    }
};

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
            $table->string('PartCode')->nullable();
            $table->string('PartName')->nullable();
            $table->string('Unit')->nullable();
            $table->string('Factor')->nullable();
            $table->string('Quantity');
            $table->string('Counted');
            $table->string('Wastage');
            $table->string('Conflict');
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

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
        Schema::create('infos', function (Blueprint $table) {
            $table->id();
            $table->string('StoreCode')->nullable();
            $table->string('StoreName')->nullable();
            $table->string('PartCode')->nullable();

            $table->string('PartName')->nullable();
            $table->string('Unit')->nullable();
            $table->string('Factor')->nullable();//
            $table->string('Quantity');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infos');
    }
};

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
            $table->string('depot_code')->nullable();
            $table->string('depot_name')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name')->nullable();
            $table->string('unit')->nullable();
            $table->string('stock_control_factor')->nullable();//
            $table->string('physical_stock');
            $table->string('counted_stock')->nullable();//
            $table->string('wastage_stock')->nullable();//
            $table->string('conflict')->nullable();

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

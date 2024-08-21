<?php

declare(strict_types=1);

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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('product_id', 36)->index();
            $table->string('colour_id', 36)->index();
            $table->string('size_id', 36)->index();
            $table->string('sku_id', 36)->index();
            $table->boolean('on_sale')->default(false);
            $table->integer('box_qty');
            $table->float('width');
            $table->float('height');
            $table->float('length');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description');
            $table->string('duration_name');
            $table->integer('duration_day');
            $table->decimal('price', 8, 2);
            $table->decimal('discount_price', 8, 2)->nullable();
            $table->date('discount_start')->nullable();
            $table->date('discount_end')->nullable();
            $table->integer('product_limit');
            $table->integer('storage_limit');
            $table->integer('variation_limit');
            $table->boolean('inventory');
            $table->boolean('pos');
            $table->boolean('support');
            $table->boolean('qr_code');
            $table->boolean('status');
            $table->integer('purchase')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}

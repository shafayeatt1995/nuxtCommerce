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
            $table->string('description');
            $table->string('duratiom');
            $table->decimal('price', 8, 2);
            $table->decimal('special_price', 8, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('product_limit');
            $table->integer('storage_limit');
            $table->integer('variation_limit');
            $table->boolean('inventory');
            $table->boolean('pos');
            $table->boolean('support');
            $table->boolean('qr_code');
            $table->boolean('status');
            $table->boolean('trial');
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

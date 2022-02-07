<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingCostRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_cost_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('state_id')->unique();
            $table->unsignedBigInteger('shipping_cost_id');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('shipping_cost_id')->references('id')->on('shipping_costs')->onDelete('cascade');
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
        Schema::dropIfExists('shipping_cost_rules');
    }
}

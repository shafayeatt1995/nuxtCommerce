<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->unsignedBigInteger('child_category_id');
            $table->unsignedBigInteger('brand_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('features');
            $table->text('description');
            $table->text('items');
            $table->text('specifications');
            $table->text('tags');
            $table->text('meta');
            $table->decimal('weight', 6, 3);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->boolean('stock')->default(true);
            $table->boolean('status')->default(true);
            $table->boolean('pending')->default(true);
            $table->boolean('suspend')->default(false);
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
            $table->foreign('child_category_id')->references('id')->on('child_categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
}

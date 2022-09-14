<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreignId('product_id')->index();
            $table->enum('product_rating', [0, 1, 2, 3, 4, 5]);
            $table->text('review')->nullable();
            $table->text('admin_comment')->nullable();
            $table->enum('status', ['waiting', 'accepted', 'canceled']);
            $table->timestamps();

            // fk
            $table->foreign('product_id')->on('products')->references('id')->onDelete('cascade');
            $table->foreign('customer_id')->on('customers')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_ratings');
    }
}

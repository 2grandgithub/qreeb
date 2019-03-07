<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTechRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_tech_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('part_id');
            $table->tinyInteger('confirmed')->default(0);
            $table->tinyInteger('taken')->default(0);
            $table->string('title');
            $table->string('desc');
            $table->string('image');
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_tech_requests');
    }
}

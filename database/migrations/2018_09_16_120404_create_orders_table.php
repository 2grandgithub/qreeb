<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type',['urgent,delayed']);
            $table->integer('company_id')->default(NULL);
            $table->integer('tech_id');
            $table->integer('user_id');
            $table->integer('code');
            $table->tinyInteger('completed')->default(0);
            $table->double('item_total')->default(NULL);
            $table->double('order_total')->default(NULL);
            $table->date('delayed_at')->default(NULL);
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
        Schema::dropIfExists('orders');
    }
}

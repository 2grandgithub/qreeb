<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechNotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tech_nots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tech_id');
            $table->integer('order_id')->default(NULL);
//            $table->enum('type',[]);
            $table->mediumText('ar_text');
            $table->mediumText('en_text');
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
        Schema::dropIfExists('tech_nots');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('address_id');
            $table->tinyInteger('active')->default(0);
            $table->string('ar_name')->unique();
            $table->string('en_name')->unique();
            $table->string('ar_desc')->unique();
            $table->string('en_desc')->unique();
            $table->string('email')->unique();
            $table->text('phones');
            $table->string('logo')->default('default_company.png');
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
        Schema::dropIfExists('companies');
    }
}

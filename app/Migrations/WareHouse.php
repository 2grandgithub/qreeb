<?php

namespace App\Migrations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class WareHouse extends Model
{
    public static function Up($id)
    {
        Schema::create($id.'_warehouse_parts', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('active')->default(1);
            $table->string('code',100);
            $table->integer('cat_id')->unsigned();
            $table->foreign('cat_id')->references('id')->on('categories')->onUpdate('cascade') ->onDelete('cascade');
//            $table->integer('min_count');
//            $table->integer('notify_count');
            $table->integer('count');
            $table->integer('requested_count')->default(0);
//            $table->integer('max_count');
            $table->double('price');
            $table->string('en_name');
            $table->string('ar_name');
            $table->string('en_desc');
            $table->string('ar_desc');
            $table->string('image', 500)->default('box.png');
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });
    }

    public static function Down($id)
    {
        Schema::dropIfExists($id.'_warehouse_parts');
    }
}

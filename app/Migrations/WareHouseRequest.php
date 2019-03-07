<?php

namespace App\Migrations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class WareHouseRequest extends Model
{
    public static function Up($id)
    {
        Schema::create($id.'_warehouse_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('tech_id')->unsigned();
            $table->foreign('tech_id')
                ->references('id')->on('technicians')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('title');
            $table->string('details');
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });
    }

    public static function Down($id)
    {
        Schema::dropIfExists($id.'_warehouse_requests');
    }
}

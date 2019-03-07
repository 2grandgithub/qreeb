<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderItemUser extends Model
{
    protected $fillable =
        [
            'order_id','user_id','provider_id','item_id'
        ];


    public function get_item($lang,$provider_id,$item_id)
    {
        $item = DB::table($provider_id.'_warehouse_parts')->where('id', $item_id)->select($lang.'_name as name',$lang.'_desc as desc','image','price','code','count')->first();
        return $item;
    }


    public function this_item()
    {
        return $this->belongsTo(Warehouse::class, 'item_id');
    }


    public function get_this_item($provider_id,$item_id)
    {
        $item = DB::table($provider_id.'_warehouse_parts')->where('id', $item_id)->select('en_name','en_desc','price','image','status')->first();
        return $item;
    }


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

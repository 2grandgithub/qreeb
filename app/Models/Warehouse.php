<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table;

    function __construct() {
        $this->table = provider()->provider_id.'_warehouse_parts';
    }

    protected $fillable = [
        'code','cat_id','count','requested_count','price','en_name','ar_name','en_desc','ar_desc'
    ];





    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }
}

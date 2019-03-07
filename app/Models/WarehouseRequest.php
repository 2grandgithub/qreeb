<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseRequest extends Model
{
    protected $table;

    function __construct() {
        $this->table = provider()->provider_id.'_warehouse_requests';

    }}

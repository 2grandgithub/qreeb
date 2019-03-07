<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class Order extends Model
{
    protected $fillable = [
        'completed','type','tech_id','scheduled_at','canceled','canceled_by'
    ];

    public static function filterbylatlng($mylat,$mylng,$radius,$model,$cat_id,$providers,$flag=null,$conditionarray=null)
    {
        $haversine = "(6371 * acos(cos(radians($mylat))
                           * cos(radians($model.lat))
                           * cos(radians($model.lng)
                           - radians($mylng))
                           + sin(radians($mylat))
                           * sin(radians($model.lat))))";
        $datainradiusrange = DB::table($model)->select('*')
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$radius])->where('cat_ids','like','%'.$cat_id.'%')->whereIn('provider_id',$providers)->where('active', 1)->where('busy', 0)->select('id', 'lat', 'lng')->get();


        return $datainradiusrange;
    }


    public static function get_category($lang,$id)
    {
        $cat = Category::where('id', $id)->select($lang.'_name as name')->first();
        return $cat->name;
    }


    public static function get_user($lang,$id)
    {
        $user = User::where('id', $id)->select($lang.'_name as name','phone','image','lat','lng')->first();
        return $user;
    }


    public static function get_tech($lang,$id)
    {
        $tech = Technician::where('id', $id)->select('provider_id',$lang.'_name as name','phone','image','lat','lng')->first();
        return $tech;
    }


    public static function get_tech_lang($lang,$id,$cat_id)
    {
        $tech = Technician::where('id', $id)->select($lang.'_name as name','image')->first();
        $tech['image'] = 'http://'.$_SERVER['SERVER_NAME'].'/public/providers/technicians/'.$tech->image;
        $tech['category']= $tech->get_category($lang,$cat_id);
        $tech['rate'] = $tech->get_all_rate($id);

        return $tech;
    }


    public static function get_tech_all($id,$cat_id)
    {
        $tech = Technician::where('id', $id)->select('en_name','ar_name','image')->first();
        $tech['image'] = 'http://'.$_SERVER['SERVER_NAME'].'/public/providers/technicians/'.$tech->image;

        $category= $tech->get_category_all($cat_id);
        $tech['ar_category'] = $category->ar_name;
        $tech['en_category'] = $category->en_name;
        $tech['rate'] = $tech->get_all_rate($id);

        return $tech;
    }


    public function get_type($lang,$type)
    {
        if($lang == 'ar')
        {
            if($type == 'urgent') $text = 'طلب عاجل';
            elseif($type == 're_scheduled') $text = 'إعادة زيارة';
            else $text = 'طلب مؤجل';
        }
        else
        {
            if($type == 'urgent') $text = 'Urgent Request';
            elseif($type == 're_scheduled') $text = 'Re-scheduled Request';
            else $text = 'Scheduled Request';
        }

        return $text;
    }


    public function get_details($order_id)
    {
        $details = OrderUserDetail::where('order_id', $order_id)->select('place', 'part', 'desc', 'images')->first();
        $new_arr = [];

        if ($details != NULL)
        {
            if ($details->images != NULL) {
                $details['images'] = unserialize($details->images);

                foreach ($details->images as $image) {
                    array_push($new_arr, 'http://' . $_SERVER['SERVER_NAME'] . '/public/orders/' . $image);
                }
            }
        }

        $details['place'] = '';
        $details['part'] = '';
        $details['desc'] = '';
        $details['images'] = $new_arr;

        return $details;
    }


    public function get_items_awaiting($lang,$order_id)
    {
        $items = OrderItemUser::where('order_id', $order_id)->select('id','provider_id','item_id','status')->get();
        foreach($items as $item)
        {
            $data = $item->get_item($lang,$item->provider_id,$item->item_id);
            $item['name'] = $data->name;
            $item['desc'] = $data->desc;
            $item['price'] = $data->price;
            $item['count'] = $data->count;
            $item['code'] = $data->code;
            $item['image'] = 'http://'.$_SERVER['SERVER_NAME'].'/public/warehouses/'.$item->get_item($lang,$item->provider_id,$item->item_id)->image;
        }

        return $items;
    }


    public function get_items($lang,$order_id)
    {
        $items = OrderTechRequest::where('order_id', $order_id)->select('provider_id','item_id','status')->get();
        foreach($items as $item)
        {
            $data = $item->get_item($lang,$item->provider_id,$item->item_id);
            $item['name'] = $data->name;
            $item['desc'] = $data->desc;
            $item['price'] = $data->price;
            $item['count'] = $data->count;
            $item['code'] = $data->code;
            $item['image'] = 'http://'.$_SERVER['SERVER_NAME'].'/public/warehouses/'.$item->get_item($lang,$item->provider_id,$item->item_id)->image;
        }

        return $items;
    }


    public function category()
    {
        return $this->belongsTo(Category::class,'cat_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }


    public function tech()
    {
        return $this->belongsTo(Technician::class,'tech_id');
    }


    public function items()
    {
        return $this->hasMany(OrderTechRequest::class,'order_id');
    }



    public function get_fee($provider_id,$cat_id)
    {
        $fee = ProviderCategoryFee::where('provider_id', $provider_id)->where('cat_id', $cat_id)->select('fee')->first()->fee;
        return $fee;
    }


    public function user_details()
    {
        return $this->hasOne(OrderUserDetail::class,'order_id');
    }


    public function tech_details()
    {
        return $this->hasOne(OrderTechDetail::class,'order_id');
    }


    public function get_steps($lang,$id)
    {
        $order = Order::where('id', $id)->select('type','completed','tech_id','scheduled_at')->first();

        $arr = [];

        if($order->type == 'urgent')
        {
            if($lang == 'ar')
            {
                $arr[0]['text'] = 'طلب الخدمة';
                $arr[0]['flag'] = 1;

                $arr[1]['text'] = 'تم إختيار الفني';
                $arr[1]['flag'] = 1;

                $arr[2]['text'] = 'الفني في الطريق';
                $arr[2]['flag'] = 1;

                $arr[3]['text'] = 'تم الإنتهاء من العمل';
                $arr[3]['flag'] = $order->completed;

            }
            else
            {
                $arr[0]['text'] = 'Service Request';
                $arr[0]['flag'] = 1;

                $arr[1]['text'] = 'Technician selected';
                $arr[1]['flag'] = 1;

                $arr[2]['text'] = 'Technician on the way';
                $arr[2]['flag'] = 1;

                $arr[3]['text'] = 'Service Completed';
                $arr[3]['flag'] = $order->completed;
            }

        }
        elseif($order->type == 'scheduled')
        {
            if($order->tech_id != null) $tech = 1;
            else $tech = 0;

            if($lang == 'ar')
            {
                $arr[0]['text'] = 'طلب الخدمة';
                $arr[0]['flag'] = 1;

                $arr[1]['text'] = 'تم إختيار الفني';
                $arr[1]['flag'] = $tech;

                $arr[2]['text'] = 'الفني في الطريق';
                $arr[2]['flag'] = $tech;

                $arr[3]['text'] = 'تم الإنتهاء من العمل';
                $arr[3]['flag'] = $order->completed;

            }
            else
            {
                $arr[0]['text'] = 'Service Request';
                $arr[0]['flag'] = 1;

                $arr[1]['text'] = 'Technician selected';
                $arr[1]['flag'] = 0;

                $arr[2]['text'] = 'Technician on the way';
                $arr[2]['flag'] = 0;

                $arr[3]['text'] = 'Service Completed';
                $arr[3]['flag'] = 0;
            }
        }
        else
        {
            $items = OrderTechRequest::where('order_id', $order->id)->pluck('status');
            if(in_array('awaiting',$items->toArray()) == false) $status = 1;
            else $status = 0;

            if($order->scheduled_at != null) $date = 1;
            else $date = 0;

            if($order->tech_id != null) $tech = 1;
            else $tech = 0;

            if($lang == 'ar')
            {
                $arr[0]['text'] = 'طلب الخدمة';
                $arr[0]['flag'] = 1;

                $arr[1]['text'] = 'تم إختيار الفني';
                $arr[1]['flag'] = 1;

                $arr[2]['text'] = 'الفني في الطريق';
                $arr[2]['flag'] = 1;

                $arr[3]['text'] = 'القيام بالصيانة';
                $arr[3]['flag'] = 1;

                $arr[4]['text'] = 'تم طلب قطع للصيانة';
                $arr[4]['flag'] = 1;

                $arr[5]['text'] = 'الموافقة علي القطع';
                $arr[5]['flag'] = $status;

                $arr[6]['text'] = 'تحديد موعد إعادة الزيارة';
                $arr[6]['flag'] = $date;

                $arr[7]['text'] = 'تم إختيار الفني';
                $arr[7]['flag'] = $tech;

                $arr[8]['text'] = 'الفني في الطريق';
                $arr[8]['flag'] = $tech;

                $arr[9]['text'] = 'القيام بالصيانة';
                $arr[9]['flag'] = $tech;

                $arr[10]['text'] = 'تم الإنتهاء من العمل';
                $arr[10]['flag'] = $order->completed;
            }
            else
            {
                $arr[0]['text'] = 'Service Request';
                $arr[0]['flag'] = 1;

                $arr[1]['text'] = 'Technician selected';
                $arr[1]['flag'] = 1;

                $arr[2]['text'] = 'Technician on the way';
                $arr[2]['flag'] = 1;

                $arr[3]['text'] = 'Maintenance On going';
                $arr[3]['flag'] = 1;

                $arr[4]['text'] = 'Maintenance parts requested';
                $arr[4]['flag'] = 1;

                $arr[5]['text'] = 'Confirming parts requests';
                $arr[5]['flag'] = $status;

                $arr[6]['text'] = 'set a second visit date';
                $arr[6]['flag'] = $date;

                $arr[7]['text'] = 'Technician selected';
                $arr[7]['flag'] = $tech;

                $arr[8]['text'] = 'Technician on the way';
                $arr[8]['flag'] = $tech;

                $arr[9]['text'] = 'Maintenance On going';
                $arr[9]['flag'] = $tech;

                $arr[10]['text'] = 'Service Completed';
                $arr[10]['flag'] = $order->completed;
            }

        }

        return $arr;
    }


    public function get_user_location($user_id)
    {
        $user = User::find($user_id);

        $camp = isset($user->camp) ? $user->camp : '';
        $street = isset($user->street) ? $user->street : '';
        $plot_no = isset($user->plot_no) ? $user->plot_no : '';
        $block_no = isset($user->block_no) ?  $user->block_no : '';
        $building_no = isset($user->building_no) ? $user->building_no : '';
        $apartment_no = isset($user->apartment_no) ? $user->apartment_no : '';

//        $location = $camp . $street . $plot_no . $block_no . $building_no . $apartment_no;

        $arr['camp'] = $camp;
        $arr['street'] = $street;
        $arr['plot_no'] = $plot_no;
        $arr['block_no'] = $block_no;
        $arr['building_no'] = $building_no;
        $arr['apartment_no'] = $apartment_no;
        $arr['lat'] = $user->lat;
        $arr['lng'] = $user->lng;

        return $arr;
    }


    public function get_user_location_admin($user_id)
    {
        $user = User::find($user_id);

        $camp = isset($user->camp) ? $user->camp : '';
        $street = isset($user->street) ? $user->street : '';
        $plot_no = isset($user->plot_no) ? $user->plot_no : '';
        $block_no = isset($user->block_no) ?  $user->block_no : '';
        $building_no = isset($user->building_no) ? $user->building_no : '';
        $apartment_no = isset($user->apartment_no) ? $user->apartment_no : '';

//        $location = $camp . $street . $plot_no . $block_no . $building_no . $apartment_no;

        $arr['Camp'] = $camp;
        $arr['Street'] = $street;
        $arr['Plot No.'] = $plot_no;
        $arr['Block No.'] = $block_no;
        $arr['Building No.'] = $building_no;
        $arr['Apartment No.'] = $apartment_no;

        return $arr;
    }


    public function get_items_total($id)
    {
        $ids = OrderTechRequest::where('order_id', $id)->where('status','confirmed')->pluck('item_id');
        $items = Warehouse::whereIn('id', $ids)->pluck('price');

        return $items->sum();
    }

    public function get_items_total2($provider_id,$id)
    {
        $ids = OrderTechRequest::where('order_id', $id)->where('status','confirmed')->pluck('item_id');
        $items = DB::table($provider_id.'_warehouse_parts')->whereIn('id', $ids)->pluck('price');

        return $items->sum();
    }


    public function get_cat_fee($order_id)
    {
        $order = Order::find($order_id);
        $fee = ProviderCategoryFee::where('provider_id', $order->provider_id)->where('cat_id', $order->cat_id)->select('fee')->first()->fee;
        return $fee;
    }
}

@extends('provider.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li>Orders</li>
        <li class="active">View Order</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <h2>Order<strong> #{{$order->id}}</strong></h2>
                        @if(isset($order->smo))
                            <h2>MSO No.<strong> #{{$order->smo}}</strong></h2>
                        @endif

                        <h2>Category : {{$order->category->parent->en_name}} - {{$order->category->en_name}}</h2>
                        {{--<div class="push-down-10 pull-right">--}}
                            {{--<button class="btn btn-default"><span class="fa fa-print"></span> Print</button>--}}
                        {{--</div>--}}
                        <!-- INVOICE -->
                        <div class="invoice">

                            <div class="row">
                                <div class="col-md-4">

                                    <div class="invoice-address">
                                        <h5>User</h5>
                                        <h6>{{$order->user->company->en_name}}</h6>
                                        <p>{{$order->user->en_name}}</p>
                                        <p>Phone: {{$order->user->phone}}</p>
                                        @foreach($order->get_user_location_admin($order->user_id) as $key => $value)
                                            <p>
                                                {{$key}} : {{$value}}
                                            </p>
                                        @endforeach
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    <div class="invoice-address">
                                        <h5>Technician</h5>
                                        @if(isset($order->tech_id))
                                            <h6>{{$order->tech->provider->en_name}}</h6>
                                            <p>{{$order->tech->en_name}}</p>
                                            <p>Phone: {{$order->tech->phone}}</p>
                                        @else
                                            <h6>Not selected yet</h6>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    <div class="invoice-address">
                                        <h5>Invoice</h5>
                                        <table class="table table-striped">
                                            <tr>
                                                <td>Created at: </td><td class="text-right">{{$order->created_at}}</td>
                                            </tr>

                                            @if($order->scheduled_at != NULL)
                                                <tr>
                                                    <td>Scheduled at: </td><td class="text-right">{{$order->scheduled_at}}</td>
                                                </tr>
                                            @endif

                                            @if(isset($order->provider_id))
                                                <tr>
                                                    <td><strong>Service Fee:</strong></td><td class="text-right"><strong>{{$order->get_fee($order->provider_id,$order->cat_id)}}</strong></td>
                                                </tr>
                                            @endif
                                        </table>

                                    </div>

                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-4">
                                    <div class="invoice-address">
                                        <h5>User Extra Details</h5>
                                        @if(isset($order->user_details->place))
                                            <h6>Place</h6>
                                            <p>{{$order->user_details->place}}</p>
                                        @endif
                                        @if(isset($order->user_details->place))
                                            <h6>Part</h6>
                                            <p>{{$order->user_details->part}}</p>
                                        @endif
                                        @if(isset($order->user_details->place))
                                            <h6>Description</h6>
                                            <p>{{$order->user_details->desc}}</p>
                                        @endif
                                        @if(isset($order->user_details->images))
                                            <div class="gallery">
                                                @foreach(unserialize($order->user_details->images) as $image)
                                                    <a class="gallery-item" href="/orders/{{$image}}" title="/orders/{{$image}}" data-gallery>
                                                        <div class="image">
                                                            <img  style="width: 94.41px; height: 94.41px; display: table; margin: 0 auto;" src="/orders/{{$image}}" alt="{{$image}}"/>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if(isset($order->tech_id) && $order->tech_details)
                                    <div class="col-md-4">
                                        <div class="invoice-address">
                                            <h5>Tech Extra Details</h5>

                                            <h6>Problem Type</h6>
                                            <p>{{$order->tech_details->category->parent->parent->en_name}} - {{$order->tech_details->category->parent->en_name}} - {{$order->tech_details->category->en_name}}</p>


                                            <h6>Description</h6>
                                            <p>{{$order->tech_details->desc}}</p>


                                            <h6>Before Maintenance</h6>
                                            <div class="gallery">
                                                @foreach(unserialize($order->tech_details->before_images) as $image)
                                                    <a class="gallery-item" href="/orders/{{$image}}" title="/orders/{{$image}}" data-gallery>
                                                        <div class="image">
                                                            <img  style="width: 94.41px; height: 94.41px; display: table; margin: 0 auto;" src="/orders/{{$image}}" alt="{{$image}}"/>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>

                                            <h6>After Maintenance</h6>
                                            <div class="gallery">
                                                @foreach(unserialize($order->tech_details->after_images) as $image)
                                                    <a class="gallery-item" href="/orders/{{$image}}" title="/orders/{{$image}}" data-gallery>
                                                        <div class="image">
                                                            <img  style="width: 94.41px; height: 94.41px; display: table; margin: 0 auto;" src="/orders/{{$image}}" alt="{{$image}}"/>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- BLUEIMP GALLERY -->
                            <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
                                <div class="slides"></div>
                                <h3 class="title"></h3>
                                <a class="prev">‹</a>
                                <a class="next">›</a>
                                <a class="close">×</a>
                                <a class="play-pause"></a>
                                <ol class="indicator"></ol>
                            </div>
                            <!-- END BLUEIMP GALLERY -->

                            @if($order->items->count() > 0)
                                <div class="table-invoice">
                                    <table class="table">
                                        <tr>
                                            <th>Item Description</th>
                                            <th class="text-center">Item Price</th>
                                            <th class="text-center">Image</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    <strong>{{$item->this_item->en_name}}</strong>
                                                    <p>{{$item->this_item->en_desc}}</p>
                                                </td>
                                                <td class="text-center">{{$item->this_item->price}} S.R</td>
                                                <td class="text-center"><img src="/warehouses/{{$item->this_item->image}}" class="image_radius"/></td>
                                                <td class="text-center">@if($item->status == 'confirmed') <span class="label label-success">Approved</span> @elseif($item->status == 'awaiting') <span class="label label-warning">Awaiting</span> @else <span class="label label-danger">Declined</span> @endif</td>
                                            </tr>
                                        @endforeach
                                        <th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">{{$order->item_total}} S.R</td>
                                        </th>
                                    </table>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Amount Due</h4>

                                    <table class="table table-striped">
                                        <tr>
                                            <td width="200"><strong>Service Fee:</strong></td><td class="text-right">{{$order->get_cat_fee($order->id)}} S.R</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Items Total:</strong></td><td class="text-right">{{$order->item_total}} S.R</td>
                                        </tr>
                                        <tr class="total">
                                            <td>Total Amount:</td><td class="text-right">{{$order->order_total}} S.R</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- END INVOICE -->

                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- END PAGE CONTENT WRAPPER -->

    <script>
        document.getElementById('links').onclick = function (event) {
            event = event || window.event;
            var target = event.target || event.srcElement;
            var link = target.src ? target.parentNode : target;
            var options = {index: link, event: event,onclosed: function(){
                    setTimeout(function(){
                        $("body").css("overflow","");
                    },200);
                }};
            var links = this.getElementsByTagName('a');
            blueimp.Gallery(links, options);
        };
    </script>

@endsection

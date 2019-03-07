@extends('provider.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li>Orders</li>
        <li class="active">Search</li>
    </ul>
    <!-- END BREADCRUMB -->

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
            @include('provider.layouts.message')
            <!-- START BASIC TABLE SAMPLE -->
                <div class="panel panel-default">
                    {{--<div class="panel-heading">--}}
                        {{--<a href="/company/order/create"><button type="button" class="btn btn-info"> Make an order </button></a>--}}
                    {{--</div>--}}

                    <form class="form-horizontal" method="get" action="/provider/orders/search">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <div class="input-group" style="margin-top: 10px;">
                                    <input type="text" class="form-control" name="search" value="{{isset($search) ? $search : ''}}" placeholder="Search by MSO No. or technician name,email or phone" style="margin-top: 1px;"/>
                                <span class="input-group-addon btn btn-default">
                                    <button class="btn btn-default">Search now</button>
                                </span>
                                </div>
                            </div>
                        </div>
                    </form>

                     <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>MSO No.</th>
                                    <th>type</th>
                                    <th>Badge ID</th>
                                    <th>Technician</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th>Operations</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{isset($order->smo) ? $order->smo : '-'}}</td>
                                        <td>{{$order->type}}</td>
                                        <td>@if(isset($order->tech_id))  {{$order->tech->badge_id}} @else Not selected yet @endif</td>
                                        <td>{{isset($order->tech_id) ? $order->tech->en_name : '-'}}</td>
                                        <td>
                                            @if($order->type == 'urgent')
                                                {{$order->created_at}}
                                            @elseif($order->type == 'scheduled')
                                                {{$order->scheduled_at}}
                                            @else
                                                {{isset($order->scheduled_at) ? $order->scheduled_at : 'Not selected yet'}}
                                            @endif
                                        </td>
                                        <td>@if($order->completed == 1) <span class="label label-success">Completed</span> @elseif($order->canceled == 1) @if($order->canceled_by == 'user') <span class="label label-danger">Canceled By User</span> @else <span class="label label-danger">Canceled By Technician</span> @endif @else <span class="label label-primary">Open</span> @endif</td>
                                        <td>{{$order->items->count()}}</td>
                                        <td>
                                            <a title="View" href="/company/order/{{$order->id}}/view"><button class="btn btn-info btn-condensed"><i class="fa fa-eye"></i></button></a>
                                            {{--<button class="btn btn-danger btn-condensed mb-control" data-box="#message-box-warning-{{$order->id}}" title="Delete"><i class="fa fa-trash-o"></i></button>--}}
                                        </td>
                                    </tr>

                                    {{--<!-- danger with sound -->--}}
                                    {{--<div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$order->id}}">--}}
                                        {{--<div class="mb-container">--}}
                                            {{--<div class="mb-middle warning-msg alert-msg">--}}
                                                {{--<div class="mb-title"><span class="fa fa-times"></span>Alert !</div>--}}
                                                {{--<div class="mb-content">--}}
                                                    {{--<p>Your are about to delete a warehouse item,and you won't be able to restore its data again .</p>--}}
                                                    {{--<br/>--}}
                                                    {{--<p>Are you sure ?</p>--}}
                                                {{--</div>--}}
                                                {{--<div class="mb-footer buttons">--}}
                                                    {{--<button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>--}}
                                                    {{--<form method="post" action="/company/warehouse/item/delete" class="buttons">--}}
                                                        {{--{{csrf_field()}}--}}
                                                        {{--<input type="hidden" name="item_code" value="{{$order->code}}">--}}
                                                        {{--<button type="submit" class="btn btn-danger btn-lg pull-right">Delete</button>--}}
                                                    {{--</form>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<!-- end danger with sound -->--}}
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

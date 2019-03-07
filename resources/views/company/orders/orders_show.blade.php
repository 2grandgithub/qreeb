@extends('company.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li>Orders</li>
        <li>Orders Info Sheet</li>
        <li class="active">Show</li>
    </ul>
    <!-- END BREADCRUMB -->

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
            @include('provider.layouts.message')
                    <!-- START DATATABLE EXPORT -->
                    <div class="panel panel-default">
                        <div class="panel-heading">

                            <!-- PAGE TITLE -->
                            <div class="btn-group pull-left">
                                <h2><span class="fa fa-wrench"> {{$type_value}} Orders</span></h2>
                                <h2><span class="fa fa-calendar"> From {{$from}} To {{$to}} </span></h2>

                            </div>
                            <!-- END PAGE TITLE -->

                            <div class="btn-group pull-right">
                                <form method="post" action="/company/orders/invoice/export">
                                    {{csrf_field()}}
                                    <input type="hidden" name="type" value="{{$type_key}}">
                                    <input type="hidden" name="from" value="{{$from}}">
                                    <input type="hidden" name="to" value="{{$to}}">
                                    <button type="submit" class="btn btn-success"> Export Orders Invoice <i class="fa fa-file-excel-o"></i> </button>
                                </form>
                            </div>
                        </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    @if($type_key == 'canceled')
                                        <th>Canceled By</th>
                                    @endif
                                    <th>Cost</th>
                                    <th class="sorting_asc" aria-sort="ascending">Total</th>
                                    <th>Operations</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{isset($order->cat_id) ? $order->category->parent->en_name . ' - ' . $order->category->en_name: ''}}</td>
                                        <td>{{isset($order->created_at) ? $order->created_at->toDateTimeString() : ''}}</td>
                                        <td>
                                            @if(isset($order->type))
                                                @if($order->type == 'urgent')
                                                    Urgent
                                                @elseif($order->type == 'scheduled')
                                                    Scheduled
                                                @else
                                                    Re-Scheduled
                                                @endif
                                            @endif
                                        </td>
                                        @if($type_key == 'canceled')
                                            <td>
                                                @if(isset($order->canceled_by) && $order->canceled_by == 'user')
                                                    User
                                                @elseif(isset($order->canceled_by) && $order->canceled_by == 'tech')
                                                    Tech
                                                @endif
                                            </td>
                                        @endif
                                        <td>{{isset($order->order_total) ? $order->order_total : ''}}</td>
                                        <td>{{isset($order['total']) ? $order['total'] : ''}}</td>
                                        <td>
                                            @if(isset($order->id))
                                                <a title="View Order" href="/company/order/{{$order->id}}/view"><button class="btn btn-info btn-condensed"><i class="fa fa-eye"></i></button></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection

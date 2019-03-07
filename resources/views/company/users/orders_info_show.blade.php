@extends('company.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/company/dashboard">Dashboard</a></li>
        <li><a href="/company/users/active">Users</a></li>
        <li>Orders Info Sheet</li>
        <li class="active">Show</li>
    </ul>
    <!-- END BREADCRUMB -->

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
            @include('company.layouts.message')
                    <!-- START DATATABLE EXPORT -->
                    <div class="panel panel-default">
                        <div class="panel-heading">

                            <!-- PAGE TITLE -->
                            <div class="btn-group pull-left">
                                <h2><span class="fa fa-user"> {{$user->name}}</span></h2>
                                <h2><span class="fa fa-calendar"> From {{$from}} To {{$to}} </span></h2>

                            </div>
                            <!-- END PAGE TITLE -->

                            <div class="btn-group pull-right">
                                <form method="post" action="/company/user/orders/invoice/export">
                                    {{csrf_field()}}
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
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

@extends('provider.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li class="active">Warehouse Requests</li>
    </ul>
    <!-- END BREADCRUMB -->

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
            @include('provider.layouts.message')
            <!-- START BASIC TABLE SAMPLE -->
                <div class="panel panel-default">
                    {{--<div class="panel-heading">--}}
                        {{--<a href="/provider/warehouse/item/create"><button type="button" class="btn btn-info"> Add a new item </button></a>--}}
                    {{--</div>--}}
                     <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{$item->order_id}}</td>
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->desc}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>
                                            <a title="View Order" href="/provider/order/{{$item->order_id}}/view"><button class="btn btn-info btn-condensed"><i class="fa fa-eye"></i></button></a>
                                        </td>
                                    </tr>

                                    <!-- danger with sound -->
                                    {{--<div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-danger-{{$item->id}}">--}}
                                        {{--<div class="mb-container">--}}
                                            {{--<div class="mb-middle warning-msg alert-msg">--}}
                                                {{--<div class="mb-title"><span class="fa fa-times"></span>Alert !</div>--}}
                                                {{--<div class="mb-content">--}}
                                                    {{--<p>Your are about to delete a warehouse item request,and you won't be able to restore its data again .</p>--}}
                                                    {{--<br/>--}}
                                                    {{--<p>Are you sure ?</p>--}}
                                                {{--</div>--}}
                                                {{--<div class="mb-footer buttons">--}}
                                                    {{--<button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>--}}
                                                    {{--<form method="post" action="/provider/warehouse_request/delete" class="buttons">--}}
                                                        {{--{{csrf_field()}}--}}
                                                        {{--<input type="hidden" name="request_id" value="{{$item->id}}">--}}
                                                        {{--<button type="submit" class="btn btn-danger btn-lg pull-right">Delete</button>--}}
                                                    {{--</form>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    <!-- end danger with sound -->
                                @endforeach

                                </tbody>
                            </table>
                            {{$items->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

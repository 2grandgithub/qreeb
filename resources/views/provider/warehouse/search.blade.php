@extends('provider.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li>Warehouse</li>
        <li>Search</li>
    </ul>
    <!-- END BREADCRUMB -->

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
            @include('provider.layouts.message')
            <!-- START BASIC TABLE SAMPLE -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @if(provider()->hasPermissionTo('warehouse_operate'))
                            <a href="/provider/warehouse/item/create"><button type="button" class="btn btn-info"> Add a new item </button></a>
                        @endif
                        @if(provider()->hasPermissionTo('warehouse_file_upload'))
                            <a href="/provider/warehouse/excel/view"><button type="button" class="btn btn-info"> Upload excel file </button></a>
                            <a href="/provider/warehouse/images/view"><button type="button" class="btn btn-info"> Upload images compressed file </button></a>
                        @endif
                    </div>

                    <form class="form-horizontal" method="get" action="/provider/warehouse/search">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <div class="input-group" style="margin-top: 10px;">
                                    <input type="text" class="form-control" name="search" value="{{isset($search) ? $search : ''}}" placeholder="Search by item name or code" style="margin-top: 1px;"/>
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
                                    <th>code</th>
                                    <th>Category</th>
                                    <th>English Name</th>
                                    <th>Arabic Name</th>
                                    <th>Count</th>
                                    <th>Image</th>
                                    @if(provider()->hasPermissionTo('warehouse_operate'))
                                        <th>Operations</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{$item->code}}</td>
                                        <td>{{$item->category->parent->en_name}} - {{$item->category->en_name}}</td>
                                        <td>{{$item->en_name}}</td>
                                        <td>{{$item->ar_name}}</td>
                                        <td>{{$item->count}}</td>
                                        <td>
                                            <img src="/warehouses/{{$item->image}}" class="image_radius"/>
                                        </td>
                                        @if(provider()->hasPermissionTo('warehouse_operate'))
                                            <td>
                                                <a title="Edit Item" href="/provider/warehouse/item/{{$item->code}}/edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                                {{--<button class="btn btn-danger btn-condensed mb-control" data-box="#message-box-warning-{{$item->id}}" title="Delete"><i class="fa fa-trash-o"></i></button>--}}
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- danger with sound -->
                                    {{--<div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$item->id}}">--}}
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
                                                    {{--<form method="post" action="/provider/warehouse/item/delete" class="buttons">--}}
                                                        {{--{{csrf_field()}}--}}
                                                        {{--<input type="hidden" name="item_code" value="{{$item->code}}">--}}
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

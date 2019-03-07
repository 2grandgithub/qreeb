@extends('provider.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li class="active">Warehouse</li>
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
                        <a href="/provider/warehouse/excel/parts/export" style="float: right;"><button type="button" class="btn btn-success"> Export Parts <i class="fa fa-file-excel-o"></i></button></a>
                        <a href="/provider/warehouse/excel/categories/export" style="float: right; margin-right: 3px;"><button type="button" class="btn btn-success"> Export Categories <i class="fa fa-file-excel-o"></i> </button></a>
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
                                    <th>Status</th>
                                    <th>code</th>
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
                                        <td>@if($item->active == 1) <span class="label label-success">Active</span> @else <span class="label label-default">Suspended</span> @endif</td>
                                        <td>{{$item->code}}</td>
                                        <td>{{$item->en_name}}</td>
                                        <td>{{$item->ar_name}}</td>
                                        <td>{{$item->count}}</td>
                                        <td>
                                            <img src="/warehouses/{{$item->image}}" class="image_radius"/>
                                        </td>
                                        @if(provider()->hasPermissionTo('warehouse_operate'))
                                            <td>
                                                <a title="Edit Item" href="/provider/warehouse/item/{{$item->code}}/edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                                @if($item->active == 0)
                                                    <button class="btn btn-success btn-condensed mb-control" data-box="#message-box-active-{{$item->id}}" title="Activate"><i class="fa fa-check-square"></i></button>
                                                @else
                                                    <button class="btn btn-primary btn-condensed mb-control" data-box="#message-box-suspend-{{$item->id}}" title="Suspend"><i class="fa fa-minus-square"></i></button>
                                                @endif
                                                {{--<button class="btn btn-danger btn-condensed mb-control" data-box="#message-box-warning-{{$item->id}}" title="Delete"><i class="fa fa-trash-o"></i></button>--}}
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- danger with sound -->
                                    <div class="message-box message-box-success animated fadeIn" data-sound="alert/fail" id="message-box-active-{{$item->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to activate a warehouse item,it will be visible to be selected by technicians .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/provider/warehouse/item/change_status" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="item_code" value="{{$item->code}}">
                                                        <button type="submit" class="btn btn-success btn-lg pull-right">Activate</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end danger with sound -->

                                    <!-- danger with sound -->
                                    <div class="message-box message-box-primary animated fadeIn" data-sound="alert/fail" id="message-box-suspend-{{$item->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to suspend a warehouse item,and it won't be able visible to be selected by technicians .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/provider/warehouse/item/change_status" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="item_code" value="{{$item->code}}">
                                                        <button type="submit" class="btn btn-primary btn-lg pull-right">Suspend</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end danger with sound -->

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

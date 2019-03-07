@extends('provider.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li>Technician</li>
        <li class="active">Search</li>
    </ul>
    <!-- END BREADCRUMB -->

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
            @include('admin.layouts.message')
            <!-- START BASIC TABLE SAMPLE -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @if(provider()->hasPermissionTo('techs_operate'))
                            <a href="/provider/technician/create"><button type="button" class="btn btn-info"> Add a new technician </button></a>
                        @endif
                        @if(provider()->hasPermissionTo('techs_file_upload'))
                            <a href="/provider/technician/excel/view"><button type="button" class="btn btn-info"> Upload excel file </button></a>
                            <a href="/provider/technician/images/view"><button type="button" class="btn btn-info"> Upload images compressed file </button></a>
                        @endif
                    </div>

                    <form class="form-horizontal" method="get" action="/provider/technicians/search">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <div class="input-group" style="margin-top: 10px;">
                                    <input type="text" class="form-control" name="search" value="{{isset($search) ? $search : ''}}" placeholder="Search by user badge id,name,email or phone" style="margin-top: 1px;"/>
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
                                                    <th>Badge ID</th>
                                                    <th>Categories</th>
                                                    <th>English Name</th>
                                                    <th>Rotation</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Image</th>
                                                    <th>Operations</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($techs as $tech)
                                                    <tr>
                                                        <td>{{$tech->badge_id}}</td>
                                                        <td>
                                                            @foreach($tech->get_category_list($tech->cat_ids) as $cat)
                                                                <p>{{$cat}}</p>
                                                            @endforeach                                                        </td>
                                                        <td>{{$tech->en_name}}</td>
                                                        <td>
                                                            @if($tech->rotation_id != NULL)
                                                                {{$tech->rotation->en_name}}<br/>
                                                                {{\Carbon\Carbon::parse($rotation->from)->format('g:i A')}} - {{\Carbon\Carbon::parse($rotation->from)->format('g:i A')}}
                                                            @else
                                                                Not Assigned
                                                            @endif
                                                        </td>
                                                        <td>{{$tech->email}}</td>
                                                        <td>{{$tech->phone}}</td>
                                                        <td>
                                                            <img src="/providers/technicians/{{$tech->image}}" class="image_radius"/>
                                                        </td>
                                                        <td>
                                                            <a title="View Technician" href="/provider/technician/{{$tech->id}}/view"><button class="btn btn-info btn-condensed"><i class="fa fa-eye"></i></button></a>
                                                            @if(provider()->hasPermissionTo('techs_operate'))
                                                            <a title="View Orders" href="/provider/technician/{{$tech->id}}/orders/request"><button class="btn btn-success btn-condensed"><i class="fa fa-file-excel-o"></i></button></a>
                                                                <a title="Edit" href="/provider/technician/{{$tech->id}}/edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                                                @if($tech->active == 1)
                                                                    <button class="btn btn-primary btn-condensed mb-control" data-box="#message-box-suspend-{{$tech->id}}" title="Suspend"><i class="fa fa-minus-square"></i></button>
                                                                @else
                                                                    <button class="btn btn-success btn-condensed mb-control" data-box="#message-box-activate-{{$tech->id}}" title="Activate"><i class="fa fa-check-square"></i></button>
                                                                @endif
                                                                {{--<button class="btn btn-danger btn-condensed mb-control" data-box="#message-box-warning-{{$tech->id}}" title="Delete"><i class="fa fa-trash-o"></i></button>--}}
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    <!-- activate with sound -->
                                                    <div class="message-box message-box-success animated fadeIn" data-sound="alert/fail" id="message-box-activate-{{$tech->id}}">
                                                        <div class="mb-container">
                                                            <div class="mb-middle warning-msg alert-msg">
                                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                                <div class="mb-content">
                                                                    <p>Your are about to activate a technician,it will now be available for orders and search.</p>
                                                                    <br/>
                                                                    <p>Are you sure ?</p>
                                                                </div>
                                                                <div class="mb-footer buttons">
                                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                                    <form method="post" action="/provider/technician/change_state" class="buttons">
                                                                        {{csrf_field()}}
                                                                        <input type="hidden" name="tech_id" value="{{$tech->id}}">
                                                                        <input type="hidden" name="state" value="1">
                                                                        <button type="submit" class="btn btn-success btn-lg pull-right">Activate</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- end activate with sound -->

                                                    <!-- suspend with sound -->
                                                    <div class="message-box message-box-primary animated fadeIn" data-sound="alert/fail" id="message-box-suspend-{{$tech->id}}">
                                                        <div class="mb-container">
                                                            <div class="mb-middle warning-msg alert-msg">
                                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                                <div class="mb-content">
                                                                    <p>Your are about to suspend a technician,and the technician wont be available for orders nor search .</p>
                                                                    <br/>
                                                                    <p>Are you sure ?</p>
                                                                </div>
                                                                <div class="mb-footer buttons">
                                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                                    <form method="post" action="/provider/technician/change_state" class="buttons">
                                                                        {{csrf_field()}}
                                                                        <input type="hidden" name="tech_id" value="{{$tech->id}}">
                                                                        <input type="hidden" name="state" value="0">
                                                                        <button type="submit" class="btn btn-primary btn-lg pull-right">Suspend</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- end suspend with sound -->

                                                    {{--<!-- danger with sound -->--}}
                                                    {{--<div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$tech->id}}">--}}
                                                        {{--<div class="mb-container">--}}
                                                            {{--<div class="mb-middle warning-msg alert-msg">--}}
                                                                {{--<div class="mb-title"><span class="fa fa-times"></span>Alert !</div>--}}
                                                                {{--<div class="mb-content">--}}
                                                                    {{--<p>Your are about to delete a technician,and you won't be able to restore its data again like orders under this technician .</p>--}}
                                                                    {{--<br/>--}}
                                                                    {{--<p>Are you sure ?</p>--}}
                                                                {{--</div>--}}
                                                                {{--<div class="mb-footer buttons">--}}
                                                                    {{--<button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>--}}
                                                                    {{--<form method="post" action="/provider/technician/delete" class="buttons">--}}
                                                                        {{--{{csrf_field()}}--}}
                                                                        {{--<input type="hidden" name="tech_id" value="{{$tech->id}}">--}}
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
                                        {{$techs->links()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
@endsection

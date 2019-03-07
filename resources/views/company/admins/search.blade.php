@extends('company.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/company/dashboard">Dashboard</a></li>
        <li>Admins</li>
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
                        <a href="/company/admin/create"><button type="button" class="btn btn-info"> Add a new admin </button></a>
                        {{--<a href="/admin/admin/excel/export"><button type="button" class="btn btn-success pull-right">Download <i class="fa fa-file-excel-o"></i></button></a>--}}
                    </div>
                    <form class="form-horizontal" method="get" action="/company/admins/search">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <div class="input-group" style="margin-top: 10px;">
                                    <input type="text" class="form-control" name="search" value="{{isset($search) ? $search : ''}}" placeholder="Search by name,email or phone" style="margin-top: 1px;"/>
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
                                    <th>Role</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Operations</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($admins as $admin)
                                    <tr>
                                        <td><label class="label label-info">{{$admin->role}}</label></td>
                                        <td>{{$admin->name}}</td>
                                        <td>{{$admin->email}}</td>
                                        <td>{{$admin->phone}}</td>
                                        <td>
                                            <img src="/companies/admins/{{$admin->image}}" class="image_radius"/>
                                        </td>
                                        <td>
                                            @if($admin->active == 1)
                                                <label class="label label-success">Active</label>
                                            @else
                                                <label class="label label-primary">Suspended</label>
                                            @endif
                                        </td>
                                        <td>
                                            <a title="View" href="/company/admin/{{$admin->id}}/view"><button class="btn btn-info btn-condensed"><i class="fa fa-eye"></i></button></a>
                                            @if(company()->hasPermissionTo('users_operate'))
                                                <a title="Edit" href="/company/admin/{{$admin->id}}/edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                                @if($admin->active == 1)
                                                    <button class="btn btn-primary btn-condensed mb-control" data-box="#message-box-suspend-{{$admin->id}}" title="Suspend"><i class="fa fa-minus-square"></i></button>
                                                @else
                                                    <button class="btn btn-success btn-condensed mb-control" data-box="#message-box-activate-{{$admin->id}}" title="Activate"><i class="fa fa-check-square"></i></button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- activate with sound -->
                                    <div class="message-box message-box-success animated fadeIn" data-sound="alert/fail" id="message-box-activate-{{$admin->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to activate a admin,and will be able to do business in the admin panel.</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/company/admin/change_state" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="admin_id" value="{{$admin->id}}">
                                                        <input type="hidden" name="state" value="1">
                                                        <button type="submit" class="btn btn-success btn-lg pull-right">Activate</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end activate with sound -->

                                    <!-- suspend with sound -->
                                    <div class="message-box message-box-primary animated fadeIn" data-sound="alert/fail" id="message-box-suspend-{{$admin->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to suspend a admin,and will not be able to do business in the admin panel any more .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/company/admin/change_state" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="admin_id" value="{{$admin->id}}">
                                                        <input type="hidden" name="state" value="0">
                                                        <button type="submit" class="btn btn-primary btn-lg pull-right">Suspend</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end suspend with sound -->
                                @endforeach

                                </tbody>
                            </table>
                            {{$admins->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

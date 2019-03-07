@extends('provider.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    @php
        if($admin->active == 1)
        {
            $name = 'Active';
        }
        elseif($admin->active == 0)
        {
            $name = 'Suspended';
        }
    @endphp

    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li>Admins</li>
        <li class="active">{{$admin->name}}</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-eye"></span> View Profile</h2>
    </div>
    <!-- END PAGE TITLE -->
    @include('admin.layouts.message')
    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-5">

                <form action="#" class="form-horizontal">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3><span class="fa fa-Admin-secret"></span> {{$admin->name}} </h3>
                            <p>
                                <span class="label label-info label-form"> {{$admin->role}} </span>
                                @if($admin->active == 1)
                                    <span class="label label-success label-form"> Active </span>
                                @elseif($admin->active == 0)
                                    <span class="label label-primary label-form"> Suspended </span>
                                @endif
                            </p>
                            <div class="text-center" id="Admin_image">
                                <img src="/providers/admins/{{$admin->image}}" class="img-thumbnail" width="300px" height="300px"/>
                            </div>
                        </div>
                        <div class="panel-body form-group-separated">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">#</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$admin->badge_id}} </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
            <div class="col-md-6 col-sm-8 col-xs-7">

                <form action="#" class="form-horizontal">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3><span class="fa fa-pencil"></span> Profile</h3>
                        </div>
                        <div class="panel-body form-group-separated">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Name</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$admin->name}} </span>
                                </div>
                            </div>
                        </div>

                        <div class="panel-body form-group-separated">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Email</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$admin->email}} </span>
                                </div>
                            </div>
                        </div>

                        <div class="panel-body form-group-separated">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Phone</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$admin->phone}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                {{--<div class="panel panel-default tabs">--}}
                    {{--<ul class="nav nav-tabs">--}}
                        {{--<li class="active"><a href="#tab1" data-toggle="tab">Send Email</a></li>--}}
                    {{--</ul>--}}
                    {{--<div class="tab-content">--}}
                        {{--<div class="tab-pane panel-body active" id="tab1">--}}
                            {{--<div class="form-group">--}}
                                {{--<label>E-mail</label>--}}
                                {{--<input type="email" class="form-control" placeholder="youremail@domain.com">--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                                {{--<label>Subject</label>--}}
                                {{--<input type="email" class="form-control" placeholder="Message subject">--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                                {{--<label>Message</label>--}}
                                {{--<textarea class="form-control" placeholder="Your message" rows="3"></textarea>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                    {{--</div>--}}

                {{--</div>--}}

            </div>

            <div class="col-md-3">
                <div class="panel panel-default form-horizontal">
                    <div class="panel-body">
                        <h3><span class="fa fa-info-circle"></span> Quick Info</h3>
                    </div>
                    <div class="panel-body form-group-separated">
                        <div class="form-group">
                            <label class="col-md-4 col-xs-5 control-label">Registration</label>
                            <div class="col-md-8 col-xs-7 line-height-30">{{$admin->created_at}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-xs-5 control-label">Last Update</label>
                            <div class="col-md-8 col-xs-7 line-height-30">
                                @if($admin->created_at == $admin->updated_at)
                                    <span class="label label-default">No updates yet</span>
                                @else
                                    {{$admin->updated_at}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3><span class="fa fa-cog"></span> Settings</h3>
                    </div>
                    <div class="panel-body form-horizontal form-group-separated">
                        <div class="form-group">
                            <label class="col-md-6 col-xs-6 control-label">Edit</label>
                            <div class="col-md-6 col-xs-6">
                                <a href="/provider/admin/{{$admin->id}}/edit"><button class="btn btn-warning">Edit</button></a>
                            </div>
                        </div>
                        @if($admin->active == 1)
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6 control-label">Suspend Admin</label>
                                <div class="col-md-6 col-xs-6">
                                    <button class="btn btn-primary mb-control" data-box="#message-box-suspend-{{$admin->id}}" title="suspend">Suspend</button>
                                </div>
                            </div>
                        @elseif($admin->active == 0)
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6 control-label">Activate Admin</label>
                                <div class="col-md-6 col-xs-6">
                                    <button class="btn btn-success mb-control" data-box="#message-box-activate-{{$admin->id}}" title="Activate">Activate</button>
                                </div>
                            </div>
                        @endif
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6 control-label">Delete Admin</label>
                                <div class="col-md-6 col-xs-6">
                                    <button class="btn btn-danger mb-control" title="Click to delete !" data-box="#message-box-warning-{{$admin->id}}">Delete</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
    <!-- activate with sound -->
    <div class="message-box message-box-success animated fadeIn" data-sound="alert/fail" id="message-box-activate-{{$admin->id}}">
        <div class="mb-container">
            <div class="mb-middle warning-msg alert-msg">
                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                <div class="mb-content">
                    <p>Your are about to activate an admin,he will now be able to log in the system and so stuff.</p>
                    <br/>
                    <p>Are you sure ?</p>
                </div>
                <div class="mb-footer buttons">
                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                    <form method="post" action="/provider/admin/change_status" class="buttons">
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
                    <p>Your are about to suspend an admin,he will now prohibited from being able to log in the system and so stuff.</p>
                    <br/>
                    <p>Are you sure ?</p>
                </div>
                <div class="mb-footer buttons">
                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                    <form method="post" action="/provider/admin/change_status" class="buttons">
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

    <!-- danger with sound -->
    <div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$admin->id}}">
        <div class="mb-container">
            <div class="mb-middle warning-msg alert-msg">
                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                <div class="mb-content">
                    <p>Your are about to delete an admin,and you won't be able to restore its data again.</p>
                    <br/>
                    <p>Are you sure ?</p>
                </div>
                <div class="mb-footer buttons">
                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                    <form method="post" action="/provider/admin/delete" class="buttons">
                        {{csrf_field()}}
                        <input type="hidden" name="admin_id" value="{{$admin->id}}">
                        <button type="submit" class="btn btn-danger btn-lg pull-right">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end danger with sound -->

    <!-- END PAGE CONTENT WRAPPER -->
@endsection

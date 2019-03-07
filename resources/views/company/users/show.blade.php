@extends('company.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    @php
        if($user->active == 1)
        {
            $state = 'active';
            $name = 'Active';
        }
        elseif($user->active == 0)
        {
            $state = 'suspended';
            $name = 'Suspended';
        }
    @endphp

    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li> <a href="/admin/technicians/{{$state}}">{{$name}} Users</a></li>
        <li class="active">{{$user->en_name}}</li>
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
                            <h3><span class="fa fa-wrench"></span> {{$user->en_name}} </h3>
                            <p>
                                @if($user->active == 1)
                                    <span class="label label-success label-form"> Active User </span>
                                @elseif($user->active == 0)
                                    <span class="label label-primary label-form"> Suspended User </span>
                                @endif
                            </p>
                            <div class="text-center" id="user_image">
                                <img src="/companies/users/{{$user->image}}" class="img-thumbnail" width="300px" height="300px"/>
                            </div>
                        </div>
                        <div class="panel-body form-group-separated">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">#Badge ID</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$user->badge_id}} </span>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Email</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$user->email}} </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Phone</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$user->phone}} </span><br/>
                                </div>
                            </div>

                            {{--<div class="form-group">--}}
                                {{--<div class="col-md-12 col-xs-12">--}}
                                    {{--<a href="#" class="btn btn-warning btn-block btn-rounded" data-toggle="modal" data-target="#modal_change_password">Change password</a>--}}
                                {{--</div>--}}
                            {{--</div>--}}

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
                                <label class="col-md-3 col-xs-5 control-label">English Name</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$user->en_name}} </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Arabic Name</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$user->ar_name}} </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Sub Company Name</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$user->sub_company->en_name}} </span>
                                </div>
                            </div>
                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-3 col-xs-5 control-label">Location</label>--}}
                                {{--<div class="col-md-9 col-xs-7">--}}
                                    {{--<span class="form-control"> {{$user->address->parent->en_name}} - {{$user->address->en_name}} </span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-3 col-xs-5 control-label">English Description</label>--}}
                                {{--<div class="col-md-9 col-xs-7">--}}
                                    {{--<textarea class="form-control" rows="5">{{$user->en_desc}}</textarea>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-3 col-xs-5 control-label">Arabic Description</label>--}}
                                {{--<div class="col-md-9 col-xs-7">--}}
                                    {{--<textarea class="form-control" rows="5">{{$user->ar_desc}}</textarea>--}}
                                {{--</div>--}}
                            {{--</div>--}}
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
                            <div class="col-md-8 col-xs-7 line-height-30">{{$user->created_at}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-xs-5 control-label">Last Update</label>
                            <div class="col-md-8 col-xs-7 line-height-30">
                                @if($user->created_at == $user->updated_at)
                                    <span class="label label-default">No updates yet</span>
                                @else
                                    {{$user->updated_at}}
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-xs-5 control-label">Orders</label>
                            <div class="col-md-8 col-xs-7 line-height-30">{{$user->orders->count()}}</div>
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
                                <a href="/company/user/{{$user->id}}/edit"><button class="btn btn-warning">Edit</button></a>
                            </div>
                        </div>
                        @if($user->active == 1)
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6 control-label">Suspend User</label>
                                <div class="col-md-6 col-xs-6">
                                    <button class="btn btn-primary mb-control" data-box="#message-box-suspend-{{$user->id}}" title="suspend">Suspend</button>
                                </div>
                            </div>
                        @elseif($user->active == 0)
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6 control-label">Activate User</label>
                                <div class="col-md-6 col-xs-6">
                                    <button class="btn btn-success mb-control" data-box="#message-box-activate-{{$user->id}}" title="Activate">Activate</button>
                                </div>
                            </div>
                        @endif
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6 control-label">Delete User</label>
                                <div class="col-md-6 col-xs-6">
                                    <button class="btn btn-danger mb-control" title="Click to delete !" data-box="#message-box-warning-{{$user->id}}">Delete</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
    <!-- activate with sound -->
    <div class="message-box message-box-success animated fadeIn" data-sound="alert/fail" id="message-box-activate-{{$user->id}}">
        <div class="mb-container">
            <div class="mb-middle warning-msg alert-msg">
                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                <div class="mb-content">
                    <p>Your are about to activate a Technician,it will now be available for orders and search.</p>
                    <br/>
                    <p>Are you sure ?</p>
                </div>
                <div class="mb-footer buttons">
                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                    <form method="post" action="/provider/technician/change_state" class="buttons">
                        {{csrf_field()}}
                        <input type="hidden" name="tech_id" value="{{$user->id}}">
                        <input type="hidden" name="state" value="1">
                        <button type="submit" class="btn btn-success btn-lg pull-right">Activate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end activate with sound -->

    <!-- suspend with sound -->
    <div class="message-box message-box-primary animated fadeIn" data-sound="alert/fail" id="message-box-suspend-{{$user->id}}">
        <div class="mb-container">
            <div class="mb-middle warning-msg alert-msg">
                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                <div class="mb-content">
                    <p>Your are about to suspend a Technician,and the Technician wont be available for orders nor search .</p>
                    <br/>
                    <p>Are you sure ?</p>
                </div>
                <div class="mb-footer buttons">
                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                    <form method="post" action="/provider/technician/change_state" class="buttons">
                        {{csrf_field()}}
                        <input type="hidden" name="tech_id" value="{{$user->id}}">
                        <input type="hidden" name="state" value="0">
                        <button type="submit" class="btn btn-primary btn-lg pull-right">Suspend</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end suspend with sound -->

    <!-- danger with sound -->
    <div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$user->id}}">
        <div class="mb-container">
            <div class="mb-middle warning-msg alert-msg">
                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                <div class="mb-content">
                    <p>Your are about to delete a Technician,and you won't be able to restore its data again like technicians,companies and orders under this Technician .</p>
                    <br/>
                    <p>Are you sure ?</p>
                </div>
                <div class="mb-footer buttons">
                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                    <form method="post" action="/provider/technician/delete" class="buttons">
                        {{csrf_field()}}
                        <input type="hidden" name="tech_id" value="{{$user->id}}">
                        <button type="submit" class="btn btn-danger btn-lg pull-right">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end danger with sound -->

    <!-- change password -->
    <div class="modal animated fadeIn" id="modal_change_password" tabindex="-1" role="dialog" aria-labelledby="smallModalHead" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="smallModalHead">Change password</h4>
                </div>
                <form method="post" action="/company/user/change_password">
                    {{csrf_field()}}
                    <div class="modal-body form-horizontal form-group-separated">
                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-3 control-label">New Password</label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password" required/>
                                @include('admin.layouts.error', ['input' => 'password'])
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-3 control-label">Repeat New</label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password_confirmation" required/>
                                @include('admin.layouts.error', ['input' => 'password_confirmation'])
                            </div>

                        </div>
                        <input type="hidden" value="{{$user->id}}" name="user_id">
                    </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Change</button>
                    </form>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    <!-- end change password -->

    <!-- END PAGE CONTENT WRAPPER -->
@endsection

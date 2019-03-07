@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    @php
        if($provider->active == 1)
        {
            $state = 'active';
            $name = 'Active';
        }
        elseif($provider->active == 0)
        {
            $state = 'suspended';
            $name = 'Suspended';
        }
    @endphp

    @if($errors->has('password') || $errors->has('password_confirmation'))
        <script>
            $(window).load(function() {
                $('#modal_change_password').modal('show');
            });
        </script>
    @endif
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li> <a href="/admin/providers/{{$state}}">{{$name}} Providers</a></li>
        <li class="active">{{$provider->en_name}}</li>
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
                            <h3><span class="fa fa-industry"></span> {{$provider->en_name}} </h3>
                            <p>
                                @if($provider->active == 1)
                                    <span class="label label-success label-form"> Active Provider </span>
                                @elseif($provider->active == 0)
                                    <span class="label label-primary label-form"> Suspended Provider </span>
                                @endif
                            </p>
                            <div class="text-center" id="user_image">
                                <img src="/providers/logos/{{$provider->logo}}" class="img-thumbnail" width="300px" height="300px"/>
                            </div>
                        </div>
                        <div class="panel-body form-group-separated">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">#ID</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$provider->id}} </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Username</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$provider->admin->username}} </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Email</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$provider->email}} </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Phones</label>
                                <div class="col-md-9 col-xs-7">
                                    @foreach(unserialize($provider->phones) as $phone)
                                        <span class="form-control"> {{$phone}} </span><br/>
                                    @endforeach
                                </div>
                            </div>

                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-3 col-xs-5 control-label">Username</label>--}}
                                {{--<div class="col-md-9 col-xs-7">--}}
                                    {{--<span class="form-control"> {{$provider->username}} </span>--}}
                                {{--</div>--}}
                            {{--</div>--}}

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
                                    <span class="form-control"> {{$provider->en_name}} </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Arabic Name</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$provider->ar_name}} </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Location</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$provider->address->parent->en_name}} - {{$provider->address->en_name}} </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">English Description</label>
                                <div class="col-md-9 col-xs-7">
                                    <label class="input-group-text" rows="5">{{$provider->en_desc}}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Arabic Description</label>
                                <div class="col-md-9 col-xs-7">
                                    <label class="input-group-text" rows="5">{{$provider->ar_desc}}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Interest Fee</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$provider->interest_fee}} </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Warehouse Fee</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$provider->warehouse_fee}} </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>

                <div class="panel panel-default tabs">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab1" data-toggle="tab">Send Email</a></li>
                    </ul>
                    <div class="tab-content">
                        <form method="post" action="/admin/mail/send">
                            {{csrf_field()}}
                            <div class="tab-pane panel-body active" id="tab1">
                                <div class="form-group">
                                    <label>E-mail</label>
                                    <input type="email" class="form-control" name="email" value="{{$provider->email}}" required>
                                    @include('admin.layouts.error', ['input' => 'email'])
                                </div>

                                <div class="form-group">
                                    <label>Subject</label>
                                    <input type="text" class="form-control" name="subject" placeholder="Message subject" required>
                                    @include('admin.layouts.error', ['input' => 'subject'])
                                </div>


                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea class="form-control" name="text" placeholder="Your message" rows="3" required></textarea>
                                    @include('admin.layouts.error', ['input' => 'text'])
                                </div>

                                <button type="submit" class="btn btn-info btn-lg pull-right">Send</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="col-md-3">
                <div class="panel panel-default form-horizontal">
                    <div class="panel-body">
                        <h3><span class="fa fa-info-circle"></span> Quick Info</h3>
                    </div>
                    <div class="panel-body form-group-separated">
                        <div class="form-group">
                            <label class="col-md-4 col-xs-5 control-label">Registration</label>
                            <div class="col-md-8 col-xs-7 line-height-30">{{$provider->created_at}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-xs-5 control-label line-height-30">Technicians</label>
                            <div class="col-md-8 col-xs-7 line-height-30">{{$provider->technicians->count()}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-xs-5 control-label line-height-30">Orders</label>
                            <div class="col-md-8 col-xs-7 line-height-30">{{$provider->orders->count()}}</div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3><span class="fa fa-cog"></span> Settings</h3>
                    </div>
                    <div class="panel-body form-horizontal form-group-separated">

                        @if($provider->active == 1)
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6 control-label">Suspend</label>
                                <div class="col-md-6 col-xs-6">
                                    <button class="btn btn-primary mb-control" data-box="#message-box-suspend-{{$provider->id}}" title="suspend">Suspend</button>
                                </div>
                            </div>
                        @elseif($provider->active == 0)
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6 control-label">Remove Suspension</label>
                                <div class="col-md-6 col-xs-6">
                                    <button class="btn btn-success mb-control" data-box="#message-box-activate-{{$provider->id}}" title="Activate">Activate</button>                                </div>
                            </div>
                        @endif
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6 control-label">Delete Provider</label>
                                <div class="col-md-6 col-xs-6">
                                    <button class="btn btn-danger mb-control" title="Click to delete !" data-box="#message-box-warning-{{$provider->id}}">Delete Provider</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
    <!-- activate with sound -->
    <div class="message-box message-box-success animated fadeIn" data-sound="alert/fail" id="message-box-activate-{{$provider->id}}">
        <div class="mb-container">
            <div class="mb-middle warning-msg alert-msg">
                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                <div class="mb-content">
                    <p>Your are about to activate a provider,it will now be available for orders and search.</p>
                    <br/>
                    <p>Are you sure ?</p>
                </div>
                <div class="mb-footer buttons">
                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                    <form method="post" action="/admin/provider/change_state" class="buttons">
                        {{csrf_field()}}
                        <input type="hidden" name="provider_id" value="{{$provider->id}}">
                        <input type="hidden" name="state" value="1">
                        <button type="submit" class="btn btn-success btn-lg pull-right">Activate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end activate with sound -->

    <!-- suspend with sound -->
    <div class="message-box message-box-primary animated fadeIn" data-sound="alert/fail" id="message-box-suspend-{{$provider->id}}">
        <div class="mb-container">
            <div class="mb-middle warning-msg alert-msg">
                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                <div class="mb-content">
                    <p>Your are about to suspend a provider,and the provider wont be available for orders nor search .</p>
                    <br/>
                    <p>Are you sure ?</p>
                </div>
                <div class="mb-footer buttons">
                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                    <form method="post" action="/admin/provider/change_state" class="buttons">
                        {{csrf_field()}}
                        <input type="hidden" name="provider_id" value="{{$provider->id}}">
                        <input type="hidden" name="state" value="0">
                        <button type="submit" class="btn btn-primary btn-lg pull-right">Suspend</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end suspend with sound -->

    <!-- danger with sound -->
    <div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$provider->id}}">
        <div class="mb-container">
            <div class="mb-middle warning-msg alert-msg">
                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                <div class="mb-content">
                    <p>Your are about to delete a provider,and you won't be able to restore its data again like technicians,companies and orders under this provider .</p>
                    <br/>
                    <p>Are you sure ?</p>
                </div>
                <div class="mb-footer buttons">
                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                    <form method="post" action="/admin/provider/delete" class="buttons">
                        {{csrf_field()}}
                        <input type="hidden" name="provider_id" value="{{$provider->id}}">
                        <button type="submit" class="btn btn-danger btn-lg pull-right">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end danger with sound -->

    <!-- change password -->
    {{--<div class="modal animated fadeIn" id="modal_change_password" tabindex="-1" role="dialog" aria-labelledby="smallModalHead" aria-hidden="true">--}}
        {{--<div class="modal-dialog">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>--}}
                    {{--<h4 class="modal-title" id="smallModalHead">Change password</h4>--}}
                {{--</div>--}}
                {{--<form method="post" action="/admin/provider/change_password">--}}
                    {{--{{csrf_field()}}--}}
                    {{--<div class="modal-body form-horizontal form-group-separated">--}}
                        {{--<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">--}}
                            {{--<label class="col-md-3 control-label">New Password</label>--}}
                            {{--<div class="col-md-9">--}}
                                {{--<input type="password" class="form-control" name="password" required/>--}}
                                {{--@include('admin.layouts.error', ['input' => 'password'])--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">--}}
                            {{--<label class="col-md-3 control-label">Repeat New</label>--}}
                            {{--<div class="col-md-9">--}}
                                {{--<input type="password" class="form-control" name="password_confirmation" required/>--}}
                                {{--@include('admin.layouts.error', ['input' => 'password_confirmation'])--}}
                            {{--</div>--}}

                        {{--</div>--}}
                        {{--<input type="hidden" value="{{$provider->id}}" name="provider_id">--}}
                    {{--</div>--}}

                {{--<div class="modal-footer">--}}
                    {{--<button type="submit" class="btn btn-warning">Change</button>--}}
                    {{--</form>--}}
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <!-- end change password -->

    <!-- END PAGE CONTENT WRAPPER -->
@endsection

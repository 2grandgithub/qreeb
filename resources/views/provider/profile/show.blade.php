@extends('provider.layouts.app')
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

    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li class="active">{{$provider->en_name}}</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-eye"></span> View Provider Info</h2>
    </div>
    <!-- END PAGE TITLE -->
    @include('provider.layouts.message')
    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-5">
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
                        </div>
                    </div>
            </div>
            <div class="col-md-6 col-sm-8 col-xs-7">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3><span class="fa fa-pencil"></span> Profile</h3>
                        </div>
                        <div class="panel-body form-group-separated form-horizontal">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Location</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$provider->address->parent->en_name}} - {{$provider->address->en_name}} </span>
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">English Name</label>
                                <div class="col-md-9 col-xs-7">
                                    <label class="form-control">{{$provider->en_name}}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Arabic Name</label>
                                <div class="col-md-9 col-xs-7">
                                    <label class="form-control">{{$provider->ar_name}}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">English Description</label>
                                <div class="col-md-9 col-xs-7">
                                    <label class="form-control" style="height: auto;">{{$provider->en_desc}}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Arabic Description</label>
                                <div class="col-md-9 col-xs-7">
                                    <label class="form-control" style="height: auto;">{{$provider->ar_desc}}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Email</label>
                                <div class="col-md-9 col-xs-7">
                                    <label class="form-control">{{$provider->email}}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Phones</label>
                                <div class="col-md-9 col-xs-7">
                                    <div id="field">
                                        @foreach(unserialize($provider->phones) as $phone)
                                            <label class="form-control">{{$phone}}</label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
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
                            <div class="col-md-8 col-xs-7 line-height-30">@if(isset($provider->orders)) {{ $provider->orders->count() }} @else <span class="label label-default">No updates yet</span> @endif</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT WRAPPER -->
@endsection

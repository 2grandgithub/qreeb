@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    @php
        if($company->active == 1)
        {
            $state = 'active';
            $name = 'Active';
        }
        elseif($company->active == 0)
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
        <li> <a href="/admin/companies/{{$state}}">{{$name}} Companies</a></li>
        <li class="active">{{$company->en_name}}</li>
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
                            <h3><span class="fa fa-industry"></span> {{$company->en_name}} </h3>
                            <p>
                                @if($company->active == 1)
                                    <span class="label label-success label-form"> Active company </span>
                                @elseif($company->active == 0)
                                    <span class="label label-primary label-form"> Suspended company </span>
                                @endif
                            </p>
                            <div class="text-center" id="user_image">
                                <img src="/companies/logos/{{$company->logo}}" class="img-thumbnail" width="300px" height="300px"/>
                            </div>
                        </div>
                        <div class="panel-body form-group-separated">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">#ID</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$company->id}} </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Email</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$company->email}} </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Phones</label>
                                <div class="col-md-9 col-xs-7">
                                    @foreach(unserialize($company->phones) as $phone)
                                        <span class="form-control"> {{$phone}} </span><br/>
                                    @endforeach
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
                                <label class="col-md-3 col-xs-5 control-label">English Name</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$company->en_name}} </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Arabic Name</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$company->ar_name}} </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Location</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$company->address->parent->en_name}} - {{$company->address->en_name}} </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">English Description</label>
                                <div class="col-md-9 col-xs-7">
                                    <textarea class="form-control" rows="5">{{$company->en_desc}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Arabic Description</label>
                                <div class="col-md-9 col-xs-7">
                                    <textarea class="form-control" rows="5">{{$company->ar_desc}}</textarea>
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
                        <div class="tab-pane panel-body active" id="tab1">
                            <div class="form-group">
                                <label>E-mail</label>
                                <input type="email" class="form-control" placeholder="youremail@domain.com">
                            </div>
                            <div class="form-group">
                                <label>Subject</label>
                                <input type="email" class="form-control" placeholder="Message subject">
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea class="form-control" placeholder="Your message" rows="3"></textarea>
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
                            <div class="col-md-8 col-xs-7 line-height-30">{{$company->created_at}}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 col-xs-5 control-label">Users</label>
                            <div class="col-md-8 col-xs-7">{{isset($company->users) ? $company->users->count() : 0}}</div>
                        </div>
                    </div>

                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3><span class="fa fa-cog"></span> Settings</h3>
                    </div>
                    <div class="panel-body form-horizontal form-group-separated">

                        @if($company->active == 1)
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6 control-label">Suspend</label>
                                <div class="col-md-6 col-xs-6">
                                    <form method="post" action="/admin/company/change_state">
                                        {{csrf_field()}}
                                        <input type="hidden" name="company_id" value="{{$company->id}}">
                                        <input type="hidden" name="state" value="0">
                                        <button type="submit" class="btn btn-primary" title="Click to suspend !">Suspend Company</button>
                                    </form>
                                </div>
                            </div>
                        @elseif($company->active == 0)
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6 control-label">Remove Suspension</label>
                                <div class="col-md-6 col-xs-6">
                                    <form method="post" action="/admin/company/change_state">
                                        {{csrf_field()}}
                                        <input type="hidden" name="company_id" value="{{$company->id}}">
                                        <input type="hidden" name="state" value="1">
                                        <button type="submit" class="btn btn-success" title="Click to remove suspension !">Remove Suspension</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6 control-label">Delete company</label>
                                <div class="col-md-6 col-xs-6">
                                    <form method="post" action="/admin/company/delete">
                                        {{csrf_field()}}
                                        <input type="hidden" name="company_id" value="{{$company->id}}">
                                        <button type="submit" class="btn btn-danger" title="Click to delete !">Delete Company</button>
                                    </form>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
    <!-- danger with sound -->
    <div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$company->id}}">
        <div class="mb-container">
            <div class="mb-middle warning-msg alert-msg">
                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                <div class="mb-content">
                    <p>Your are about to delete a company,and you won't be able to restore its data again like users and orders under this company .</p>
                    <br/>
                    <p>Are you sure ?</p>
                </div>
                <div class="mb-footer buttons">
                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                    <form method="post" action="/admin/company/delete" class="buttons">
                        {{csrf_field()}}
                        <input type="hidden" name="company_id" value="{{$company->id}}">
                        <button type="submit" class="btn btn-danger btn-lg pull-right">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end danger with sound -->

    <!-- END PAGE CONTENT WRAPPER -->
@endsection

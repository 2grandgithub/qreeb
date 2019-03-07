@extends('company.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/company/dashboard">Dashboard</a></li>
        <li>Orders</li>
        <li class="active">
            Make An Order
        </li>
    </ul>
    <!-- END BREADCRUMB -->

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="/company/order/store">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Make An Order
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Type</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control select">
                                            <option selected disabled>Select a type</option>
                                            @foreach($types as $key => $type)
                                                <option value="{{$key}}">{{$type}}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-mail-forward"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'type'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">User</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control select">
                                            <option selected disabled>Select a user</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->en_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'user_id'])
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('cat_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Main Category</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control select" id="main_cat">
                                            <option selected disabled>Select A Main Category</option>
                                            @foreach($cats as $cat)
                                                <option value="{{$cat->id}}">{{$cat->en_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-cubes"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'cat_id'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('cat_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Sub Category</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control" id="sub_cats">
                                            <option selected disabled>Select A Category First</option>
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-cube"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'cat_id'])
                                </div>
                            </div>


                        </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Reset</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                              Order
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection

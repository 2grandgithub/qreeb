@extends('company.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/company/dashboard">Dashboard</a></li>
        <li>Users</li>
        <li class="active">
            Make An Order
        </li>
    </ul>
    <!-- END BREADCRUMB -->

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="/company/user/order/store">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Make An Order
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('mso') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">MSO</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="mso"/>
                                        <span class="input-group-addon"><span class="fa fa-hashtag"></span></span>
                                    </div>
                                    <span class="label label-primary">Optional</span>
                                    @include('admin.layouts.error', ['input' => 'mso'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Type</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control select" name="type" id="type" data-style="btn-success" required>
                                            <option selected disabled>Select a type</option>
                                            @foreach($types as $key => $type)
                                                <option value="{{$key}}" @if($key == 'urgent') selected @endif>{{$type}}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-mail-forward"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'type'])
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('scheduled_at') ? ' has-error' : '' }} timed" style="display: none;">
                                <label class="col-md-3 col-xs-12 control-label">Date</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="date" value="{{\Carbon\Carbon::now()->toDateString()}}"/>
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'scheduled_at'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('scheduled_at') ? ' has-error' : '' }} timed" style="display: none;">
                                <label class="col-md-3 col-xs-12 control-label">Time</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="time" class="form-control" name="time" value="{{\Carbon\Carbon::now()->toTimeString()}}"/>
                                        <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'scheduled_at'])
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">User</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <label class="form-control">
                                            {{$user->en_name}}
                                        </label>
                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                    </div>
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    @include('admin.layouts.error', ['input' => 'user_id'])
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('cat_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Main Category</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control select" id="main_cats" required>
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
                                        <select class="form-control" name="cat_id" data-style="btn-success" id="sub_cats" required>
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

    <script>
        $('#type').on('change', function(e)
        {
            if(e.target.value == 'scheduled')
            {
                $('.timed').show();
            }
            else
            {
                $('.timed').hide();
            }
        });

        $('#main_cats').on('change', function (e) {
            var parent_id = e.target.value;
            if (parent_id) {
                $.ajax({
                    url: '/company/get_sub_cats_company/'+parent_id,
                    type: "GET",

                    dataType: "json",

                    success: function (data) {
                        $('#sub_cats').empty();
                        $('#sub_cats').append('<option selected disabled> Select a Sub Category </option>');
                        $.each(data, function (i, sub_cat) {
                            $('#sub_cats').append('<option value="' + sub_cat.id + '">' + sub_cat.en_name + '</option>');
                        });
                    }
                });
            }
        });
    </script>

@endsection

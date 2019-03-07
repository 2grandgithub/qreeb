@extends('provider.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li><a href="/provider/technicians/active">Technicians</a></li>
        <li class="active">{{isset($technician) ? 'Update a technician' : 'Create a technician'}}</li>
    </ul>
    <!-- END BREADCRUMB -->
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                @include('admin.layouts.message')
                <form class="form-horizontal" method="post" action="{{isset($technician) ? '/provider/technician/update' : '/provider/technician/store'}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                {{isset($technician) ? 'Update an technician' : 'Create an technician'}}
                            </h3>
                        </div>

                        @if(isset($technician))
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">Categories</label>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="input-group">
                                            @foreach($technician->get_category_list($technician->cat_ids) as $cat)
                                                <p class="form-control">{{$technician->get_category_parent('en',$cat)}} - {{$cat}}</p>
                                            @endforeach
                                            <span class="input-group-addon"><span class="fa fa-cubes"></span></span>
                                        </div>
                                    </div>
                                </div>
                        @endif
                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('parent_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Main Category</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control select" id="category">
                                            <option selected disabled>Select a category</option>
                                            @foreach($cats as $cat)
                                                <option value="{{$cat->id}}">{{$cat->en_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-cubes"></span></span>
                                    </div>
                                    @if(isset($technician))
                                        <span class="label label-warning">Leave it there if no changes</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('cat_ids') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Sub Category</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control" id="sub_cats" name="cat_ids[]" multiple @if(isset($technician) == false) required @endif style="height: 150px;">
                                                <option disabled selected>Select a category first,please !</option>
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-cube"></span></span>
                                    </div>
                                    @if(isset($technician))
                                        <span class="label label-warning">Leave it there if no changes</span>
                                    @endif
                                    @include('admin.layouts.error', ['input' => 'cat_ids'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('work_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Badge ID</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="badge_id" value="{{isset($technician) ? $technician->badge_id : old('badge_id')}}" required/>
                                        <span class="input-group-addon"><span class="fa fa-id-badge"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'badge_id'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('en_name') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">English Name</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="en_name" value="{{isset($technician) ? $technician->en_name : old('en_name')}}" required/>
                                        <span class="input-group-addon"><span class="fa fa-info-circle"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'en_name'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Arabic Name</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="ar_name" value="{{isset($technician) ? $technician->ar_name : old('ar_name')}}" required/>
                                        <span class="input-group-addon"><span class="fa fa-info-circle"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'ar_name'])
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('rotation_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Rotation</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control select" name="rotation_id">
                                            <option selected disabled>Select a rotation</option>
                                            @foreach($rotations as $rotation)
                                                <option value="{{$rotation->id}}" @if(isset($technician) && $technician->rotation_id == $rotation->id) selected @endif>{{$rotation->en_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Email</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="email" required @if(isset($technician)) value="{{$technician->email}}" @else {{old('email')}} @endif/>
                                        <span class="input-group-addon"><span class="fa fa-envelope-o"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'email'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Phone</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="phone" @if(isset($technician)) value="{{$technician->phone}}" @else {{old('phone')}} @endif required/>
                                        <span class="input-group-addon"><span class="fa fa-phone"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'phone'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Image</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="file" class="fileinput btn-info" name="image" id="cp_photo" data-filename-placement="inside" title="Select Image"/>
                                    </div>
                                    @if(isset($technician))
                                        <span class="label label-warning">Leave it there if no changes</span>
                                    @endif
                                    @include('admin.layouts.error', ['input' => 'image'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Password</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" {{isset($technician) ? : 'required' }}/>
                                        <span class="input-group-addon"><span class="fa fa-asterisk"></span></span>
                                    </div>
                                    @if(isset($technician))
                                        <span class="label label-warning">Leave it there if no changes</span>
                                    @endif
                                    @include('admin.layouts.error', ['input' => 'password'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Re-Type Password</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password_confirmation" {{isset($technician) ? : 'required' }}/>
                                        <span class="input-group-addon"><span class="fa fa-asterisk"></span></span>
                                    </div>
                                    @if(isset($technician))
                                        <span class="label label-warning">Leave it there if no changes</span>
                                    @endif
                                    @include('admin.layouts.error', ['input' => 'password_confirmation'])
                                </div>
                            </div>

                            @if(isset($technician))
                                <input type="hidden" name="tech_id" value="{{$technician->id}}">
                            @endif
                        </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Reset</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                                {{isset($technician) ? 'Update' : 'Create'}}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>

    <script>
        function add_phone()
        {
            var row = '<input type="text" class="form-control phone" placeholder="Phone No." name="phones[]" style="margin-top: 5px;"/>';
            $('#field').append(row);
        }


        $('#category').on('change', function (e) {
            var parent_id = e.target.value;
            if (parent_id) {
                $.ajax({
                    url: '/provider/get_sub_cats/'+parent_id,
                    type: "GET",

                    dataType: "json",

                    success: function (data) {
                        $.each(data, function (i, sub_cat) {
                            $('#sub_cats').append('<option value="' + sub_cat.id + '">' + sub_cat.parent.en_name+' - '+ sub_cat.en_name + '</option>');
                        });
                    }
                });
            }
        });

    </script>
@endsection

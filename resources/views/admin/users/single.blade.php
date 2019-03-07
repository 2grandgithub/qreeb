@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li>Providers</li>
        <li class="active">{{isset($user) ? 'Update a provider' : 'Create a provider'}}</li>
    </ul>
    <!-- END BREADCRUMB -->
    {{--{{dd($errors)}}--}}
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="{{isset($user) ? '/admin/user/update' : '/admin/user/store'}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                {{isset($user) ? 'Update a user' : 'Create a user'}}
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('parent_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Country</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control select" id="country">
                                            <option selected disabled>Select a country</option>
                                            @foreach($addresses as $address)
                                                <option value="{{$address->id}}" @if(isset($provider) && $provider->parent_id == $address->id) selected @endif>{{$address->en_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-flag"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'parent_id'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('address_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">City</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control" id="city" name="address_id" required>
                                            <option disabled selected>Select a country first,please !</option>
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-flag"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'address_id'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('company_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Company</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control select" id="country">
                                            <option selected disabled>Select a company</option>
                                            <option value="no_company">Individual User - No Company</option>
                                            @foreach($companies as $company)
                                                <option value="{{$company->id}}" @if(isset($user) && $user->company_id == $company->id) selected @endif>{{$company->en_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-industry"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'parent_id'])
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Name</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="name" required @if(isset($user)) value="{{$user->name}}" @else {{old('name')}} @endif/>
                                        <span class="input-group-addon"><span class="fa fa-file-text"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'name'])
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Email</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="email" required @if(isset($user)) value="{{$user->email}}" @else {{old('email')}} @endif/>
                                        <span class="input-group-addon"><span class="fa fa-envelope-o"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'email'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Phone</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <div id="field">
                                            <input type="text" class="form-control phone" name="phone" required/>
                                        </div>
                                        <span class="input-group-addon"><span class="fa fa-phone"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'phone'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Image</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="file" class="fileinput btn-info" name="image" id="cp_photo" data-filename-placement="inside" title="Select image" required/>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'image'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Username</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="username" value="{{old('username')}}" required/>
                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'username'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Password</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" required/>
                                        <span class="input-group-addon"><span class="fa fa-asterisk"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'password'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Re-Type Password</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password_confirmation" required/>
                                        <span class="input-group-addon"><span class="fa fa-asterisk"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'password_confirmation'])
                                </div>
                            </div>

                            @if(isset($user))
                                <input type="hidden" name="user_id" value="{{$user->id}}">
                            @endif
                        </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Reset</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                                {{isset($user) ? 'Update' : 'Create'}}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        $('#country').on('change', function (e) {
            var parent_id = e.target.value;
            if (parent_id) {
                $.ajax({
                    url: '/admin/get_cities/'+parent_id,
                    type: "GET",

                    dataType: "json",

                    success: function (data) {
                        $('#city').empty();
                        $('#city').append('<option selected disabled> Select a city </option>');
                        $.each(data, function (i, city) {
                            $('#city').append('<option value="' + city.id + '">' + city.en_name + '</option>');
                        });
                    }
                });
            }
        });

    </script>
@endsection

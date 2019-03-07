@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li>Providers</li>
        <li class="active">{{isset($provider) ? 'Update a provider' : 'Create a provider'}}</li>
    </ul>
    <!-- END BREADCRUMB -->
    {{--{{dd($errors)}}--}}
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="{{isset($provider) ? '/admin/provider/update' : '/admin/provider/store'}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                {{isset($provider) ? 'Update an provider' : 'Create an provider'}}
                            </h3>
                        </div>
                        <div class="panel-body">

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">
                                    <h2 style="color: #33414E">
                                        Provider Info
                                    </h2>
                                </label>
                            </div>

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
                                    @if(isset($provider))
                                        <span class="label label-warning">Leave it there if no changes</span>
                                    @endif
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
                                    @if(isset($provider))
                                        <span class="label label-warning">Leave it there if no changes</span>
                                    @endif
                                    @include('admin.layouts.error', ['input' => 'address_id'])
                                </div>
                            </div>

                            @if(isset($provider))
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">Full Address</label>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="input-group">
                                            @if(isset($provider))
                                                <label class="form-control">
                                                    {{$provider->address->parent->en_name}} -  {{$provider->address->en_name}}
                                                </label>
                                            @endif
                                            <span class="input-group-addon"><span class="fa fa-map-marker"></span></span>
                                        </div>
                                        @include('admin.layouts.error', ['input' => 'en_name'])
                                    </div>
                                </div>
                            @endif

                            <div class="form-group {{ $errors->has('en_name') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">English Name</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="en_name" required @if(isset($provider)) value="{{$provider->en_name}}" @else value="{{old('en_name')}}" @endif/>
                                        <span class="input-group-addon"><span class="fa fa-file-text"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'en_name'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Arabic Name</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="ar_name" required @if(isset($provider)) value="{{$provider->ar_name}}" @else value="{{old('ar_name')}}" @endif/>
                                        <span class="input-group-addon"><span class="fa fa-file-text"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'ar_name'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('en_desc') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">English Description</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <textarea class="form-control" name="en_desc" rows="5" required>{{isset($provider) ? $provider->en_desc : old('en_desc')}}</textarea>
                                        <span class="input-group-addon"><span class="fa fa-file-text"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'en_desc'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('ar_desc') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Arabic Description</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <textarea class="form-control" name="ar_desc" rows="5" required>{{isset($provider) ? $provider->ar_desc : old('ar_desc')}}</textarea>                                        <span class="input-group-addon"><span class="fa fa-file-text"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'ar_desc'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Email</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="email" required @if(isset($provider)) value="{{$provider->email}}" @else value="{{old('email')}}" @endif/>
                                        <span class="input-group-addon"><span class="fa fa-envelope-o"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'email'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('phones') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Phones</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <div id="field">
                                            @if(isset($provider))
                                                @foreach(unserialize($provider->phones) as $phone)
                                                    <input type="text" class="form-control phone" value="{{$phone}}" name="phones[]"/>
                                                @endforeach
                                            @else
                                                <input type="text" class="form-control phone" placeholder="Phone No." name="phones[]" required/>
                                            @endif
                                        </div>
                                        <span class="input-group-addon"><span class="fa fa-phone"></span></span>
                                    </div>
                                    <a><button type="button" onclick="add_phone();" class="btn btn-primary" style="margin-top: 5px;">Add one more phone field</button></a>
                                    @include('admin.layouts.error', ['input' => 'phones'])
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('interest_fee') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Interest Fee</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="number" min="0" class="form-control" name="interest_fee" required @if(isset($provider)) value="{{$provider->interest_fee}}" @else value="{{old('interest_fee')}}" @endif/>
                                        <span class="input-group-addon"><span class="fa fa-dollar"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'interest_fee'])
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('warehouse_fee') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Warehouse Fee</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="number" min="0" class="form-control" name="warehouse_fee" required @if(isset($provider)) value="{{$provider->warehouse_fee}}" @else value="{{old('warehouse_fee')}}" @endif/>
                                        <span class="input-group-addon"><span class="fa fa-dollar"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'warehouse_fee'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('logo') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Logo</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="file" class="fileinput btn-info" name="logo" id="cp_photo" data-filename-placement="inside" title="Select file" @if(isset($provider) == false) required @endif/>
                                    </div>
                                    @if(isset($provider))
                                        <span class="label label-warning">Leave it there if no changes</span>
                                    @endif
                                    <br/>
                                    @include('admin.layouts.error', ['input' => 'logo'])
                                    @if(isset($provider))
                                        <img src="/providers/logos/{{$provider->logo}}" style="width: 300px; height: 300px; margin-top: 3px; border: #33414E solid 1px;">
                                    @endif
                                </div>
                            </div>


                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">
                                        <h2 style="color: #33414E">
                                            Super Admin Info
                                        </h2>
                                    </label>
                                </div>

                                <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                                    <label class="col-md-3 col-xs-12 control-label">Username</label>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="username" value="{{isset($provider) ? $provider->admin->username : old('username')}}" required/>
                                            <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                        </div>
                                        @include('admin.layouts.error', ['input' => 'username'])
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="col-md-3 col-xs-12 control-label">Password</label>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" @if(isset($provider) == false) required @endif/>
                                            <span class="input-group-addon"><span class="fa fa-asterisk"></span></span>
                                        </div>
                                        @include('admin.layouts.error', ['input' => 'password'])
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label class="col-md-3 col-xs-12 control-label">Re-Type Password</label>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password_confirmation" @if(isset($provider) == false) required @endif/>
                                            <span class="input-group-addon"><span class="fa fa-asterisk"></span></span>
                                        </div>
                                        @include('admin.layouts.error', ['input' => 'password_confirmation'])
                                    </div>
                                </div>

                            @if(isset($provider) == false)
                                <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                                    <label class="col-md-3 col-xs-12 control-label">Mobile</label>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="mobile" value="{{old('mobile')}}" required/>
                                            <span class="input-group-addon"><span class="fa fa-mobile"></span></span>
                                        </div>
                                        @include('admin.layouts.error', ['input' => 'mobile'])
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('badge_id') ? ' has-error' : '' }}">
                                    <label class="col-md-3 col-xs-12 control-label">Badge ID</label>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="badge_id" value="{{old('badge_id')}}"required/>
                                            <span class="input-group-addon"><span class="fa fa-id-badge"></span></span>
                                        </div>
                                        @include('admin.layouts.error', ['input' => 'badge_id'])
                                    </div>
                                </div>
                            @endif


                            @if(isset($provider))
                                <input type="hidden" name="provider_id" value="{{$provider->id}}">
                            @endif
                        </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Reset</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                                {{isset($provider) ? 'Update' : 'Create'}}
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

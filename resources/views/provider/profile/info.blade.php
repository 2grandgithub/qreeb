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
                        </div>
                    </div>
                </form>

            </div>
            <div class="col-md-6 col-sm-8 col-xs-7">
                <form method="post" action="/provider/info/update" class="form-horizontal" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3><span class="fa fa-pencil"></span> Profile</h3>
                        </div>
                        <div class="panel-body form-group-separated">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Location</label>
                                <div class="col-md-9 col-xs-7">
                                    <span class="form-control"> {{$provider->address->parent->en_name}} - {{$provider->address->en_name}} </span>
                                    <span class="label label-warning">Leave it there if no changes</span>
                                    <br/>
                                    <br/>
                                    <select class="form-control select" id="country">
                                        <option selected disabled>Select a country</option>
                                        @foreach($addresses as $address)
                                            <option value="{{$address->id}}" @if(isset($provider) && $provider->parent_id == $address->id) selected @endif>{{$address->en_name}}</option>
                                        @endforeach
                                    </select>
                                    <br/>
                                    <br/>
                                    <select class="form-control" id="city" name="address_id">
                                        <option selected disabled>Select a country first</option>
                                    </select>
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">English Name</label>
                                <div class="col-md-9 col-xs-7">
                                    <input class="form-control" name="en_name" value="{{$provider->en_name}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Arabic Name</label>
                                <div class="col-md-9 col-xs-7">
                                    <input class="form-control" name="ar_name" value="{{$provider->ar_name}}"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">English Description</label>
                                <div class="col-md-9 col-xs-7">
                                    <textarea class="form-control" name="en_desc" rows="5">{{$provider->en_desc}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Arabic Description</label>
                                <div class="col-md-9 col-xs-7">
                                    <textarea class="form-control" rows="5" name="ar_desc">{{$provider->ar_desc}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Email</label>
                                <div class="col-md-9 col-xs-7">
                                    <input class="form-control" name="email" value="{{$provider->email}}"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Phones</label>
                                <div class="col-md-9 col-xs-7">
                                    <div id="field">
                                    @foreach(unserialize($provider->phones) as $phone)
                                        <input class="form-control" name="phones[]" value="{{$phone}}" style="margin-bottom: 2px;"/>
                                    @endforeach
                                    </div>
                                <a><button type="button" onclick="add_phone();" class="btn btn-primary" style="margin-top: 5px;">Add one more phone field</button></a>
                            </div>
                        </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Logo</label>
                                <div class="col-md-9 col-xs-7">
                                    <div class="input-group">
                                        <input type="file" class="fileinput btn-info" name="logo" id="cp_photo" data-filename-placement="inside" title="Select Image"/>
                                    </div>
                                    <span class="label label-warning">Leave it there if no changes</span>
                                    @include('admin.layouts.error', ['input' => 'logo'])
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
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
                            <div class="col-md-8 col-xs-7 line-height-30">{{ $provider->orders->count() }}</div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT WRAPPER -->

    <script>
        function add_phone()
        {
            var row = '<input type="text" class="form-control phone" placeholder="Phone No." name="phones[]" style="margin-bottom: 2px;"/>';
            $('#field').append(row);
        }


        $('#country').on('change', function (e) {
            var parent_id = e.target.value;
            if (parent_id) {
                $.ajax({
                    url: '/provider/get_cities/'+parent_id,
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

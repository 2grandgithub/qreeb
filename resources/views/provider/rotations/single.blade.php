@extends('provider.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li> <a href="/provider/rotations/index">Rotations</a></li>
        <li class="active">{{isset($rotation) ? 'Update a rotation' : 'Create a rotation'}}</li>
    </ul>
    <!-- END BREADCRUMB -->

    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="{{isset($rotation) ? '/provider/rotation/update' : '/provider/rotation/store'}}">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                {{isset($rotation) ? 'Update an rotation' : 'Create an rotation'}}
                            </h3>
                        </div>

                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('en_name') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">English Name</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="en_name" value="@if(isset($rotation)) {{$rotation->en_name}} @else {{old('en_name')}} @endif" required/>
                                        <span class="input-group-addon"><span class="fa fa-repeat"></span></span>
                                    </div>
                                    @include('provider.layouts.error', ['input' => 'en_name'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Arabic Name</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="ar_name" value="@if(isset($rotation)) {{$rotation->ar_name}} @else {{old('ar_name')}} @endif" required/>
                                        <span class="input-group-addon"><span class="fa fa-repeat"></span></span>
                                    </div>
                                    @include('provider.layouts.error', ['input' => 'ar_name'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('from') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">From</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="time" class="form-control" name="from" value="@if(isset($rotation)){{\Carbon\Carbon::parse($rotation->from)->format('H:i')}}@else{{old('from')}}@endif" required/>
                                        <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                    </div>
                                    @include('provider.layouts.error', ['input' => 'from'])
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('to') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">To</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="time" class="form-control" name="to" value="@if(isset($rotation)){{\Carbon\Carbon::parse($rotation->to)->format('H:i')}}@else{{old('to')}}@endif" required/>
                                        <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                                    </div>
                                    @include('provider.layouts.error', ['input' => 'to'])
                                </div>
                            </div>

                            @if(isset($rotation))
                                <input type="hidden" name="rotation_id" value="{{$rotation->id}}">
                            @endif
                        </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Reset</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                                {{isset($rotation) ? 'Update' : 'Create'}}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection

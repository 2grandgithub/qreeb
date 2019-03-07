@extends('company.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/company/dashboard">Dashboard</a></li>
        <li> <a href="/company/collaborations">Collaborations</a></li>
        <li class=" active">Excel Sheet Request</li>
    </ul>
    <!-- END BREADCRUMB -->

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="/company/collaborations/excel_sheet/download">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                               Excel Sheet Request
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('provider_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Provider</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control select" name="'provider_id" required>
                                            @foreach($providers as $provider)
                                                <option value="{{$provider->id}}" @if($provider->id == $provider_id) selected @endif>{{$provider->en_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-industry"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'provider_id'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('from') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">From Date : </label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="from" required>
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'from'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('to') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">To Date : </label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="to" required>
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'to'])
                                </div>
                            </div>

                        </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Reset</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                                Download
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection

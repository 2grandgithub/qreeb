@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li> <a href="/admin/collaborations">Collaborations</a></li>
        <li class="active">{{isset($collaboration) ? 'Update' : 'Create'}}</li>
    </ul>
    <!-- END BREADCRUMB -->
    {{--{{dd($errors)}}--}}
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="{{isset($collaboration) ? '/admin/collaboration/update' : '/admin/collaboration/store'}}">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                {{isset($collaboration) ? 'Update' : 'Create'}}
                            </h3>
                        </div>
                        <div class="panel-body">


                            <div class="form-group {{ $errors->has('provider_id') ? ' has-error' : '' }}">
                                    <label class="col-md-3 col-xs-12 control-label">Provider</label>
                                    <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                @if(isset($collaboration))
                                                    <label class="form-control">{{$provider->en_name}}</label>
                                                    <input type="hidden" name="provider_id" value="{{$provider->id}}">
                                                @else
                                                    <select class="form-control select" name="provider_id">
                                                        <option selected disabled>Please choose from the followings</option>
                                                        @foreach($providers as $provider)
                                                            <option value="{{$provider->id}}">{{$provider->en_name}}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                                <span class="input-group-addon"><span class="fa fa-industry"></span></span>
                                            </div>
                                        @include('admin.layouts.error', ['input' => 'provider_id'])
                                    </div>
                                </div>


                                <div class="form-group {{ $errors->has('companies') ? ' has-error' : '' }}">
                                    <label class="col-md-3 col-xs-12 control-label">Companies</label>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="input-group">
                                            <select class="form-control select" name="companies[]" multiple>
                                                @foreach($companies as $company)
                                                    <option value="{{$company->id}}" @if(isset($collaboration) && in_array($company->id,$collaboration->pluck('company_id')->toArray())) selected @endif>{{$company->en_name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-addon"><span class="fa fa-building"></span></span>
                                        </div>
                                        @include('admin.layouts.error', ['input' => 'companies'])
                                    </div>
                                </div>


                        </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Reset</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                                {{isset($collaboration) ? 'Update' : 'Create'}}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection

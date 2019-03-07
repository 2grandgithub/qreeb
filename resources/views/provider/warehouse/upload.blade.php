@extends('provider.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li>Warehouse</li>
        <li class="active">Upload warehouse excel file</li>
    </ul>
    <!-- END BREADCRUMB -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                @include('provider.layouts.message')

                <form class="form-horizontal" method="post" action="/provider/warehouse/excel/upload" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                               Upload File
                            </h3>
                        </div>
                        <div class="panel-body">

                            <div class="form-group {{ $errors->has('file') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">File</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="file" class="fileinput btn-info" name="file" id="cp_photo" data-filename-placement="inside" title="Select Excel File"/>
                                    </div>
                                    @include('provider.layouts.error', ['input' => 'file'])
                                    @include('provider.layouts.error', ['input' => 'code'])
                                    @include('provider.layouts.error', ['input' => 'cat_id'])
                                    @include('provider.layouts.error', ['input' => 'count'])
                                    @include('provider.layouts.error', ['input' => 'price'])
                                    @include('provider.layouts.error', ['input' => 'en_name'])
                                    @include('provider.layouts.error', ['input' => 'ar_name'])
                                    @include('provider.layouts.error', ['input' => 'en_desc'])
                                    @include('provider.layouts.error', ['input' => 'ar_desc'])
                                </div>
                            </div>

                        </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Reset</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                               Upload
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>


@endsection

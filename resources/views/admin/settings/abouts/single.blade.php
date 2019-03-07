@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Home</a></li>
        <li>Application Settings</li>
        <li class="active">Edit about us text</li>
    </ul>
    <!-- END BREADCRUMB -->
{{--{{dd($errors)}}--}}
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" action="/admin/settings/about/update" method="post">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Edit about us text
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('en_text') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">English Text</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-file-text"></span></span>
                                        <textarea class="form-control summernote" name="en_text" rows="10">{{$about->en_text }}</textarea>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'en_text'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('ar_text') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Arabic Text</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-file-text"></span></span>
                                        <textarea class="form-control summernote" name="ar_text" rows="10">{{$about->ar_text }}</textarea>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'ar_text'])
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Clear</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                             Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- THIS PAGE PLUGINS -->
    <script type='text/javascript' src='{{asset("admin/js/plugins/icheck/icheck.min.js")}}'></script>
    <script type="text/javascript" src="{{asset("admin/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("admin/js/plugins/summernote/summernote.js")}}"></script>
    <!-- END PAGE PLUGINS -->
@endsection

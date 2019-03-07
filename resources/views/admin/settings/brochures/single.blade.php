@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">الرئيسية</a></li>
        <li>إعدادت التطبيق</li>
        <li class="active">تعديل Brochure</li>
    </ul>
    {{--<!-- END BREADCRUMB -->--}}

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" action="/admin/settings/brochure/update" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                تعديل Brochure
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('text') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">العنوان</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-file-text"></span></span>
                                        <input type="text" class="form-control" name="text" value="{{$brochure->text}}">
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'text'])
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('file') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">ملف ال Pdf</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-file-text"></span></span>
                                        <label class="form-control">{{$brochure->file}}</label>
                                        <label class="form-control" style="color: red;">إتركه فارغاً إذا لم ترد التعديل</label>
                                        <input type="file" class="form-control" name="file">
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'file'])
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="brochure_id" value="{{$brochure->id}}">
                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">تفريغ</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                             تعديل
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection

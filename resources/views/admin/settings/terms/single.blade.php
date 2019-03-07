@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">الرئيسية</a></li>
        <li>إعدادت التطبيق</li>
        <li class="active">تعديل نص القواعد و الشروط</li>
    </ul>
    <!-- END BREADCRUMB -->
{{--{{dd($errors)}}--}}
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="/admin/settings/terms/update">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                تعديل نص القواعد و الشروط
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('text') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">النص بالعربية</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-file-text"></span></span>
                                        <textarea class="form-control" name="text" rows="5">{{$terms->text }}</textarea>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'text'])
                                </div>
                            </div>
                        </div>

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

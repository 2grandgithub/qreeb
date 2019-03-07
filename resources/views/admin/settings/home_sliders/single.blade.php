@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">الرئيسية</a></li>
        <li>إعدادت التطبيق</li>
        <li><a href="/admin/settings/home_slider">سلايدر الرئيسية</a></li>
        <li class="active">@if(isset($slider)) تعديل صورة @else إضافة صورة @endif</li>
    </ul>
    <!-- END BREADCRUMB -->
{{--{{dd($errors)}}--}}
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" @if(isset($slider)) action="/admin/settings/home_slider/update" @else action="/admin/settings/home_slider/store" @endif enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                 {{isset($slider) ? 'تعديل صورة': 'إضافة صورة'}}
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('link') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">لينك التحويل</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-link"></span></span>
                                        <input type="text" class="form-control" name="link" value="{{isset($slider) ? $slider->link : old('link')}}"/>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'link'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('expire_at') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">تاريخ الإنتهاء</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-times"></span></span>
                                        <input type="date" class="form-control" name="expire_at" value="{{isset($slider) ?$slider->expire_at:old('expire_at')}}"/>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'expire_at'])
                                </div>
                            </div>

                            @if(isset($slider))
                            <div class="form-group">
                                <label class="col-md-3 control-label">الصورة الحالية</label>

                                    <div class="col-md-6">
                                        <img src="{{asset('/home_sliders/'.$slider->image)}}" class="img-responsive"/>
                                    </div>
                                @include('admin.layouts.error', ['input' => 'file'])
                            </div>
                            @endif

                            <div class="form-group {{ $errors->has('file') ? ' has-error' : '' }}">
                                <label class="col-md-3 control-label">أرفق صورة</label>
                                <div class="col-md-9">
                                    <input type="file" name="file" data-filename-placement="inside" title="إرفق صورة" style="float: right;"/>
                                </div>
                                @include('admin.layouts.error', ['input' => 'file'])
                            </div>
                            @if(isset($slider))
                                <input type="hidden" name="slider_id" value="{{$slider->id}}">
                            @endif

                        </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">تفريغ</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                              {{isset($slider) ? 'تعديل': 'إضافة'}}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection

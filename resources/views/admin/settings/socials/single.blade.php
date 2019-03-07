@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">الرئيسية</a></li>
        <li>إعدادت التطبيق</li>
        <li><a href="/admin/settings/home_slider">سلايدر الرئيسية</a></li>
        <li class="active">@if(isset($social)) تعديل حساب @else إضافة حساب @endif</li>
    </ul>
    <!-- END BREADCRUMB -->
{{--{{dd($errors)}}--}}
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" @if(isset($social)) action="/admin/settings/social/update" @else action="/admin/settings/social/store" @endif enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                 {{isset($social) ? 'تعديل حساب': 'إضافة حساب'}}
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('account') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">الحساب</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-link"></span></span>
                                        <input type="text" class="form-control" name="account" value="{{isset($social) ? $social->account : old('account')}}"/>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'account'])
                                </div>
                            </div>

                            {{--@if(isset($social))--}}
                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-3 control-label">الصورة الحالية</label>--}}

                                    {{--<div class="col-md-6">--}}
                                        {{--<img src="{{asset('/socials/'.$social->image)}}" class="img-responsive"/>--}}
                                    {{--</div>--}}
                                {{--@include('admin.layouts.error', ['input' => 'image'])--}}
                            {{--</div>--}}
                            {{--@endif--}}

                            {{--<div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">--}}
                                {{--<label class="col-md-3 control-label">أرفق صورة</label>--}}
                                {{--<div class="col-md-9">--}}
                                    {{--<input type="file" name="image" data-filename-placement="inside" title="إرفق صورة" style="float: right;"/>--}}
                                {{--</div>--}}
                                {{--@include('admin.layouts.error', ['input' => 'image'])--}}
                            {{--</div>--}}
                            @if(isset($social))
                                <input type="hidden" name="social_id" value="{{$social->id}}">
                            @endif

                        </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">تفريغ</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                              {{isset($social) ? 'تعديل': 'إضافة'}}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection

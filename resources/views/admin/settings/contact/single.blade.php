@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">الرئيسية</a></li>
        <li>إعدادت التطبيق</li>
        <li class="active">رد علي رسالة إتصل بنا</li>
    </ul>
    <!-- END BREADCRUMB -->
{{--{{dd($errors)}}--}}
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                               رد علي رسالة إتصل بنا
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">الإسم</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-info-circle"></span></span>
                                    <label>{{$contact->name}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">البريد الإلكتروني</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-info-circle"></span></span>
                                        <label>{{$contact->email}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">الهاتف</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-info-circle"></span></span>
                                        <label>{{$contact->phone}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">الرسالة</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-info-circle"></span></span>
                                        <textarea disabled class="form-control" rows="4">{{$contact->text}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form class="form-horizontal" method="post" action="/admin/contact_us/reply">
                            {{csrf_field()}}
                            <div class="form-group {{ $errors->has('reply') ? ' has-error' : '' }}">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">الرد</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-reply-all"></span></span>
                                                <textarea class="form-control" rows="7" name="reply"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'reply'])
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="reset" class="btn btn-default">تفريغ</button> &nbsp;
                                <button class="btn btn-primary pull-right">
                                    أرسل رد
                                </button>
                            </div>
                        </form>


                    </div>

            </div>
        </div>

    </div>
@endsection

@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">الرئيسية</a></li>
        <li><a>مستخدمين التطبيق</a></li>
        <li class="active">تعيين الصلاحيات</li>
    </ul>
    <!-- END BREADCRUMB -->
    {{--{{dd($errors)}}--}}
    <div class="page-content-wrap">
{{--<{{dd($errors)}}--}}
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="/admin/user/assign">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <strong>
                                    إختر الصلاحيات من الآتي :
                                </strong>
                            </h3>
                        </div>
                        <div class="panel-body">
                            @foreach($permissions as $p)
                                <div class="form-group {{ $errors->has('permissions') ? ' has-error' : '' }}">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <label class="col-md-6 control-label">{{$p->name}}</label>
                                            <label class="switch">
                                                <input type="checkbox" class="form-control" name="permissions[]" value="{{$p->name}}" @if($user->hasPermissionTo($p)) checked @endif />
                                                <span></span>
                                            </label>
                                        </div>
                                        @include('admin.layouts.error', ['input' => 'permissions'])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">تفريغ</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                                تعيين
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection

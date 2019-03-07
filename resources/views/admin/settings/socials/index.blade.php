@extends('admin.layouts.app')
@section('content')
    @if($errors->has('account') || $errors->has('image'))
        <script>
            $(window).load(function() {
                $('#modal_create').modal('show');
            });
        </script>
    @endif
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">الرئيسية</a></li>
        <li>إعدادات التطبيق</li>
        <li class="active">حسابات التواصل الإجتماعي</li>
    </ul>
    <!-- END BREADCRUMB -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12 col-xs-12">
            @include('admin.layouts.message')
            <!-- START BASIC TABLE SAMPLE -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a data-toggle="modal" data-target="#modal_create">
                            <button type="button" class="btn btn-info">أضف حساب جديد</button>
                        </a>
                    </div>
                    <div class="panel-body" style="overflow: auto;">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th class="rtl_th">#</th>
                                    <th class="rtl_th">الإسم</th>
                                    <th class="rtl_th">الحساب</th>
                                    <th class="rtl_th">الإجراء المتخذ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($socials as $social)
                                    <tr>
                                        <td>{{$social->id}}</td>
                                        <td>
                                            {{$social->name}}
                                        </td>
                                        <td>
                                            {{$social->account}}
                                        </td>
                                        <td>
                                            <a href="/admin/settings/social/{{$social->id}}/edit" title="تعديل" class="buttons"><button class="btn btn-info btn-condensed"><i class="fa fa-edit"></i></button></a>
                                            <button class="btn btn-danger btn-condensed mb-control" data-box="#message-box-warning-{{$social->id}}" title="حذف"><i class="fa fa-trash-o"></i></button>
                                        </td>
                                    </tr>
                                    <!-- danger with sound -->
                                    <div class="message-box message-box-warning animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$social->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span> الرجاء الإنتباه</div>
                                                <div class="mb-content">
                                                    <p>أنت علي وشك أن تحذف هذالحساب من الإعدادات و لن تستطيع إسترجاعه مرة أخري,هل أنت متأكد ؟</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <form method="post" action="/admin/settings/social/delete" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="social_id" value="{{$social->id}}">
                                                        <button type="submit" class="btn btn-default btn-lg pull-right">حذف</button>
                                                    </form>
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-right: 5px;">إلغاء</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end danger with sound -->
                        @endforeach
                        </tbody>

                        </table>
                        {{$socials->links()}}
                            <div class="modal animated fadeIn" id="modal_create" tabindex="-1" role="dialog" aria-labelledby="smallModalHead" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">إغلاق</span></button>
                                            <h4 class="modal-title" id="smallModalHead">إضافة حساب جديد</h4>
                                        </div>
                                        <form method="post" action="/admin/settings/social/store" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div class="modal-body form-horizontal form-group-separated">
                                                <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                                    <label class="col-md-3 control-label">الإسم</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" name="name"/>
                                                        @include('admin.layouts.error', ['input' => 'name'])

                                                    </div>
                                                </div>
                                                <div class="form-group {{ $errors->has('account') ? ' has-error' : '' }}">
                                                    <label class="col-md-3 control-label">الحساب</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" name="account"/>
                                                        @include('admin.layouts.error', ['input' => 'account'])

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">إنشاء</button>
                                            </div>
                                        </form>
                                        <button type="reset" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection

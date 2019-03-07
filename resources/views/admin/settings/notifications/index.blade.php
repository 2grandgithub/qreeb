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
        <li class="active">الإشعارات العامة</li>
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
                            <button type="button" class="btn btn-info">أرسل إشعار جديد</button>
                        </a>
                    </div>
                    <div class="panel-body" style="overflow: auto;">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th class="rtl_th">#</th>
                                    <th class="rtl_th">النص</th>
                                    <th class="rtl_th">منذ</th>
                                    <th class="rtl_th">الإجراء المتخذ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($nots as $not)
                                    <tr>
                                        <td>{{$not->id}}</td>
                                        <td>
                                            {{$not->text}}
                                        </td>
                                        <td>
                                            {{$not->created_at->diffForHumans()}}
                                        </td>
                                        <td>
                                            <a href="/admin/settings/notification/delete/{{$not->id}}" title="حذف" class="buttons"><button class="btn btn-danger btn-condensed"><i class="fa fa-trash"></i></button></a>

                                        </td>
                                    </tr>
                        @endforeach
                        </tbody>

                        </table>
                        {{$nots->links()}}
                            <div class="modal animated fadeIn" id="modal_create" tabindex="-1" role="dialog" aria-labelledby="smallModalHead" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">إغلاق</span></button>
                                            <h4 class="modal-title" id="smallModalHead">إرسال إشعار جديد</h4>
                                        </div>
                                        <form method="post" action="/admin/settings/notification/store">
                                            {{csrf_field()}}
                                            <div class="modal-body form-horizontal form-group-separated">
                                                <div class="form-group {{ $errors->has('text') ? ' has-error' : '' }}">
                                                    <label class="col-md-3 control-label">النص</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" name="text"/>
                                                        @include('admin.layouts.error', ['input' => 'text'])

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">إرسال</button>
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

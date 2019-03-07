@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">الرئيسية</a></li>
        <li>إعدادات التطبيق</li>
        <li class="active">رسائل إتصل بنا</li>
    </ul>
    <!-- END BREADCRUMB -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12  col-xs-12">
            @include('admin.layouts.message')
            <!-- START BASIC TABLE SAMPLE -->
                <div class="panel panel-default">
                    <div class="panel-body" style="overflow: auto;">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th class="rtl_th">البريد الإلكتروني</th>
                                    <th class="rtl_th">الإسم</th>
                                    <th class="rtl_th">رقم الهاتف</th>
                                    <th class="rtl_th">الرسالة</th>
                                    <th class="rtl_th">الإجراء المتخذ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($contacts as $contact)
                                    <tr>
                                        <td> {{$contact->email}} </td>
                                        <td> {{$contact->name}} </td>
                                        <td> {{$contact->phone}} </td>
                                        <td> {{$contact->message}} </td>
                                        <td>
                                            <a href="/admin/contact_us/{{$contact->id}}/view" title="رد" class="buttons"><button class="btn btn-info btn-condensed"><i class="fa fa-edit"></i></button></a>
                                            <a href="/admin/contact_us/{{$contact->id}}/delete"><button class="btn btn-danger btn-condensed mb-control" title="حذف"><i class="fa fa-trash-o"></i></button></a>
                                        </td>
                                    </tr>
                                @endforeach
                        </tbody>

                        </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection

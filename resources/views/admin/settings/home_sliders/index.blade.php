@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">الرئيسية</a></li>
        <li>إعدادات التطبيق</li>
        <li class="active">سلايدر الرئيسية</li>
    </ul>
    <!-- END BREADCRUMB -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12 col-xs-12">
            @include('admin.layouts.message')
            <!-- START BASIC TABLE SAMPLE -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="/admin/settings/home_slider/create">
                            <button type="button" class="btn btn-info">أضف صورة للسلايدر</button>
                        </a>
                    </div>
                    <div class="panel-body" style="overflow: auto;">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="rtl_th">#</th>
                                    <th class="rtl_th">الصورة</th>
                                    <th class="rtl_th">لينك التحويل</th>
                                    <th class="rtl_th">متاحة حتي</th>
                                    <th class="rtl_th">الإجراء المتخذ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sliders as $image)
                                    <tr @if($image->expire_at < \Carbon\Carbon::today()) class="danger" @else class="success" @endif>
                                        <td>{{$image->id}}</td>
                                        <td>
                                                <img class="img-responsive" src="{{asset('/home_sliders/'.$image->image)}}" width="250" height="250"/>
                                        </td>
                                        <td>
                                            {{$image->link}}
                                        </td>
                                        <td>
                                            @if($image->expire_at < \Carbon\Carbon::today()) منتهية @else {{$image->expire_at}} @endif

                                        </td>
                                        <td>
                                            <a href="/admin/settings/home_slider/{{$image->id}}/edit" title="تعديل" class="buttons"><button class="btn btn-info btn-condensed"><i class="fa fa-edit"></i></button></a>
                                            <button class="btn btn-danger btn-condensed mb-control" data-box="#message-box-warning-{{$image->id}}" title="حذف"><i class="fa fa-trash-o"></i></button>
                                        </td>
                                    </tr>
                                    <!-- danger with sound -->
                                    <div class="message-box message-box-warning animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$image->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span> الرجاء الإنتباه</div>
                                                <div class="mb-content">
                                                    <p>أنت علي وشك أن تحذف هذالصورة من سلايدر الرئيسية و لن تستطيع إسترجاعها مرة أخري,هل أنت متأكد ؟</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <form method="post" action="/admin/settings/slider/delete" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="slider_id" value="{{$image->id}}">
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
                        {{$sliders->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection

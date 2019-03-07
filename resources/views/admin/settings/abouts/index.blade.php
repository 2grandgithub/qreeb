@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Home</a></li>
        <li>Application Settings</li>
        <li class="active">About Us</li>
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
                                    <th class="rtl_th">English Text</th>
                                    <th class="rtl_th">Arabic Text</th>
                                    <th class="rtl_th">Operations</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            {!! $about->en_text !!}
                                        </td>
                                        <td>
                                            {!! $about->ar_text !!}
                                        </td>
                                        @if(admin()->hasPermissionTo('settings_operate'))
                                            <td>
                                                <a href="/admin/settings/about/edit" title="Edit" class="buttons"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                            </td>
                                        @endif
                                    </tr>

                        </tbody>

                        </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection

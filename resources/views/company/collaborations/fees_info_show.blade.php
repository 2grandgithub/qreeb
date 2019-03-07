@extends('company.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/company/dashboard">Dashboard</a></li>
        <li><a href="/company/collaborations">Collaborations</a></li>
        <li>Fees Info Sheet</li>
        <li class="active">Show</li>
    </ul>
    <!-- END BREADCRUMB -->

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
            @include('company.layouts.message')
                    <!-- START DATATABLE EXPORT -->
                    <div class="panel panel-default">
                        <div class="panel-heading">

                            <!-- PAGE TITLE -->
                            <div class="btn-group pull-left">
                                <h2><span class="fa fa-industry">{{$provider->name}}</span></h2>
                            </div>

                            <!-- END PAGE TITLE -->

                            <div class="btn-group pull-right">
                                <a href="/company/collaboration/{{$provider->id}}/fees/export" style="float: right; margin-right: 3px;"><button type="button" class="btn btn-success"> Export Categories Fees <i class="fa fa-file-excel-o"></i> </button></a>
                            </div>

                        </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Fee</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($subs as $sub)
                                    <tr>
                                        <td>{{$sub->category->parent->en_name}} - {{$sub->category->en_name}}</td>
                                        <td>{{$sub->fee}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

            </div>
        </div>
    </div>

    <!-- START THIS PAGE PLUGINS-->
    <script type='text/javascript' src='{{asset("admin/js/plugins/icheck/icheck.min.js")}}'></script>
    <script type="text/javascript" src="{{asset('admin/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/js/plugins/tableexport/tableExport.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/js/plugins/tableexport/jquery.base64.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/js/plugins/tableexport/html2canvas.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/js/plugins/tableexport/jspdf/libs/sprintf.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/js/plugins/tableexport/jspdf/jspdf.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/js/plugins/tableexport/jspdf/libs/base64.js')}}"></script>
    <!-- END THIS PAGE PLUGINS-->

    <script>
        $(document).ready(function() {
            $('#customers2').dataTable({
                "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
                "iDisplayLength": 'All',
            });
        } );
    </script>
@endsection

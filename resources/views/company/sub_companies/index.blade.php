@extends('company.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/company/dashboard">Dashboard</a></li>
        <li>Sub Companies</li>
        <li class="active">{{isset($status) ? $status : 'Search'}}</li>
    </ul>



    <!-- END BREADCRUMB -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
            @include('company.layouts.message')
            <!-- START BASIC TABLE SAMPLE -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="/company/sub_company/create"><button type="button" class="btn btn-info"> Add a new sub company </button></a>
                    </div>
                    <form class="form-horizontal" method="get" action="/company/sub_company/search">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <div class="input-group" style="margin-top: 10px;">
                                    <input type="text" class="form-control" name="search" value="{{isset($search) ? $search : ''}}" placeholder="Search by name" style="margin-top: 1px;"/>
                                    <span class="input-group-addon btn btn-default">
                                            <button class="btn btn-default">Search now</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>English Name</th>
                                    <th>Arabic Name</th>
                                    <th>Users</th>
                                    @if(company()->hasPermissionTo('sub_companies_operate'))
                                        <th>Operations</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($subs as $sub)
                                    <tr>
                                        <td>{{$sub->id}}</td>
                                        <td>{{$sub->en_name}}</td>
                                        <td>{{$sub->ar_name}}</td>
                                        <td>
                                            {{$sub->users->count()}}
                                        </td>
                                        @if(company()->hasPermissionTo('sub_companies_operate'))
                                            <td>
                                                @if($sub->users->count() != 0)
                                                    <a title="View Users" href="/company/sub_company/{{$sub->id}}/users"><button class="btn btn-info btn-condensed"><i class="fa fa-eye"></i></button></a>
                                                @endif
                                                    <a title="Edit" href="/company/sub_company/{{$sub->id}}/edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                                    @if($sub->status == 'active')
                                                        <button class="btn btn-primary btn-condensed mb-control" data-box="#message-box-suspend-{{$sub->id}}" title="Suspend"><i class="fa fa-minus-square"></i></button>
                                                    @else
                                                        <button class="btn btn-success btn-condensed mb-control" data-box="#message-box-activate-{{$sub->id}}" title="Activate"><i class="fa fa-check-square"></i></button>
                                                    @endif
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- danger with sound -->
                                    <div class="message-box message-box-primary animated fadeIn" data-sound="alert/fail" id="message-box-suspend-{{$sub->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                    <div class="mb-content">
                                                        <p>Your are about to suspend a sub company,its individuals won't be able to use the application .</p>
                                                        <br/>
                                                        <p>Are you sure ?</p>
                                                    </div>
                                                    <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/company/sub_company/status/change" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="sub_id" value="{{$sub->id}}">
                                                        <button type="submit" class="btn btn-primary btn-lg pull-right">Suspend</button>
                                                    </form>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end danger with sound -->

                                    <!-- danger with sound -->
                                    <div class="message-box message-box-success animated fadeIn" data-sound="alert/fail" id="message-box-activate-{{$sub->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to activate a sub company,its individuals will be able to use the application .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/company/sub_company/status/change" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="sub_id" value="{{$sub->id}}">
                                                        <button type="submit" class="btn btn-success btn-lg pull-right">Activate</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end danger with sound -->

                                @endforeach
                                </tbody>
                            </table>
                            {{$subs->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

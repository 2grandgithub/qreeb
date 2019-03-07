@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li>Companies</li>
        @if(Request::is('admin/companies/active'))
            <li class="active">Active</li>
        @else
            <li class="active">Suspended</li>
        @endif
    </ul>
    <!-- END BREADCRUMB -->


    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
            @include('admin.layouts.message')
            <!-- START BASIC TABLE SAMPLE -->
                <div class="panel panel-default">
                    @if(admin()->hasPermissionTo('companies_operate'))
                        <div class="panel-heading">
                            <a href="/admin/company/create"><button type="button" class="btn btn-info"> Add a new company </button></a>
                        </div>
                    @endif
                    <form class="form-horizontal" method="get" action="/admin/companies/search">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <div class="input-group" style="margin-top: 10px;">
                                    <input type="text" class="form-control" name="search" value="{{isset($search) ? $search : ''}}" placeholder="Search by name or email" style="margin-top: 1px;"/>
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
                                    <th>English Name</th>
                                    <th>Arabic Name</th>
                                    <th>Email</th>
                                    <th>Phones</th>
                                    <th>logo</th>
                                    @if(admin()->hasPermissionTo('companies_operate'))
                                        <th>Operations</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($companies as $company)
                                    <tr>
                                        <td>{{$company->en_name}}</td>
                                        <td>{{$company->ar_name}}</td>
                                        <td>{{$company->email}}</td>
                                        <td>
                                            @foreach(unserialize($company->phones) as $phone)
                                                <span>{{$phone}}</span><br/>
                                            @endforeach
                                        </td>
                                        <td>
                                            <img src="/companies/logos/{{$company->logo}}" class="image_radius"/>
                                        </td>
                                        @if(admin()->hasPermissionTo('companies_operate'))
                                            <td>
                                                <a title="View Provider" href="/admin/company/{{$company->id}}/view"><button class="btn btn-info btn-condensed"><i class="fa fa-eye"></i></button></a>
                                                <a title="Statistics" href="/admin/company/{{$company->id}}/statistics"><button class="btn btn-info btn-condensed"><i class="fa fa-area-chart"></i></button></a>
                                                <a title="Subscriptions" href="/admin/company/{{$company->id}}/subscriptions"><button class="btn btn-warning btn-condensed"><i class="fa fa-check-square"></i></button></a>
                                                @if($company->active == 1)
                                                    <button class="btn btn-primary btn-condensed mb-control" data-box="#message-box-suspend-{{$company->id}}" title="Suspend"><i class="fa fa-thumbs-o-down"></i></button>
                                                @else
                                                    <button class="btn btn-success btn-condensed mb-control" data-box="#message-box-activate-{{$company->id}}" title="Activate"><i class="fa fa-thumbs-up"></i></button>
                                                @endif
                                                <a title="Edit" href="/admin/company/{{$company->id}}/edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                                <button class="btn btn-danger btn-condensed mb-control" data-box="#message-box-warning-{{$company->id}}" title="Delete"><i class="fa fa-trash-o"></i></button>
                                            </td>
                                        @endif
                                    </tr>
                                    <!-- activate with sound -->
                                    <div class="message-box message-box-success animated fadeIn" data-sound="alert/fail" id="message-box-activate-{{$company->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to activate a company,it will now be available for orders and search.</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/company/change_state" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="company_id" value="{{$company->id}}">
                                                        <input type="hidden" name="state" value="1">
                                                        <button type="submit" class="btn btn-success btn-lg pull-right">Activate</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end activate with sound -->

                                    <!-- suspend with sound -->
                                    <div class="message-box message-box-primary animated fadeIn" data-sound="alert/fail" id="message-box-suspend-{{$company->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to suspend a company,and the provider wont be available for orders nor search .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/company/change_state" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="company_id" value="{{$company->id}}">
                                                        <input type="hidden" name="state" value="0">
                                                        <button type="submit" class="btn btn-primary btn-lg pull-right">Suspend</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end suspend with sound -->

                                    <!-- danger with sound -->
                                    <div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$company->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to delete a company,and you won't be able to restore its data again like technicians,companies and orders under this provider .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/company/delete" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="company_id" value="{{$company->id}}">
                                                        <button type="submit" class="btn btn-danger btn-lg pull-right">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end danger with sound -->
                                @endforeach
                                </tbody>
                            </table>
                            {{$companies->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

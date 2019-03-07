@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li>Providers</li>
        @if(Request::is('admin/providers/active'))
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
                    @if(admin()->hasPermissionTo('providers_operate'))
                        <div class="panel-heading">
                            <a href="/admin/provider/create"><button type="button" class="btn btn-info"> Add a new provider </button></a>
                        </div>
                    @endif
                    <form class="form-horizontal" method="get" action="/admin/providers/search">
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
                                    <th>Logo</th>
                                    @if(admin()->hasPermissionTo('providers_operate'))
                                        <th>Operations</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($providers as $provider)
                                    <tr>
                                        <td>{{$provider->en_name}}</td>
                                        <td>{{$provider->ar_name}}</td>
                                        <td>{{$provider->email}}</td>
                                        <td>
                                            @foreach(unserialize($provider->phones) as $phone)
                                                <span>{{$phone}}</span><br/>
                                            @endforeach
                                        </td>
                                        <td>
                                            <img src="/providers/logos/{{$provider->logo}}" class="image_radius"/>
                                        </td>
                                        @if(admin()->hasPermissionTo('providers_operate'))
                                            <td>
                                                <a title="View Provider" href="/admin/provider/{{$provider->id}}/view"><button class="btn btn-info btn-condensed"><i class="fa fa-eye"></i></button></a>
                                                <a title="Statistics" href="/admin/provider/{{$provider->id}}/statistics"><button class="btn btn-info btn-condensed"><i class="fa fa-area-chart"></i></button></a>
                                                <a title="Subscriptions" href="/admin/provider/{{$provider->id}}/subscriptions"><button class="btn btn-warning btn-condensed"><i class="fa fa-check-square"></i></button></a>
                                                @if($provider->active == 1)
                                                        <button class="btn btn-primary btn-condensed mb-control" data-box="#message-box-suspend-{{$provider->id}}" title="Suspend"><i class="fa fa-minus-square"></i></button>
                                                @else
                                                    <button class="btn btn-success btn-condensed mb-control" data-box="#message-box-activate-{{$provider->id}}" title="Activate"><i class="fa fa-check-square"></i></button>
                                                @endif
                                                <a title="Edit" href="/admin/provider/{{$provider->id}}/edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                                <button class="btn btn-danger btn-condensed mb-control" data-box="#message-box-warning-{{$provider->id}}" title="Delete"><i class="fa fa-trash-o"></i></button>
                                            </td>
                                        @endif
                                    </tr>
                                    <!-- activate with sound -->
                                    <div class="message-box message-box-success animated fadeIn" data-sound="alert/fail" id="message-box-activate-{{$provider->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to activate a provider,it will now be available for orders and search.</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/provider/change_state" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="provider_id" value="{{$provider->id}}">
                                                        <input type="hidden" name="state" value="1">
                                                        <button type="submit" class="btn btn-success btn-lg pull-right">Activate</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end activate with sound -->

                                    <!-- suspend with sound -->
                                    <div class="message-box message-box-primary animated fadeIn" data-sound="alert/fail" id="message-box-suspend-{{$provider->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to suspend a provider,and the provider wont be available for orders nor search .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/provider/change_state" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="provider_id" value="{{$provider->id}}">
                                                        <input type="hidden" name="state" value="0">
                                                        <button type="submit" class="btn btn-primary btn-lg pull-right">Suspend</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end suspend with sound -->

                                    <!-- danger with sound -->
                                    <div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$provider->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to delete a provider,and you won't be able to restore its data again like technicians,companies and orders under this provider .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/provider/delete" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="provider_id" value="{{$provider->id}}">
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
                            {{$providers->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

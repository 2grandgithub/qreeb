@extends('company.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/company/dashboard">Dashboard</a></li>
        <li>Users</li>
        @if(Request::is('company/users/active'))
            <li class="active">Active</li>
        @elseif(Request::is('company/users/search'))
            <li class="active">Search</li>
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

                    <div class="panel-heading">
                        @if(company()->hasPermissionTo('users_operate'))
                            <a href="/company/user/create"><button type="button" class="btn btn-info"> Add a new user </button></a>
                        @endif
                        @if(company()->hasPermissionTo('file_upload'))
                            <a href="/company/user/excel/view"><button type="button" class="btn btn-info"> Upload excel file </button></a>
                            <a href="/company/user/images/view"><button type="button" class="btn btn-info"> Upload images compressed file </button></a>
                        @endif
                    </div>
                    <form class="form-horizontal" method="get" action="/company/users/search">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-12">
                                <div class="input-group" style="margin-top: 10px;">
                                    <input type="text" class="form-control" name="search" value="{{isset($search) ? $search : ''}}" placeholder="Search by user badge_id,name,email or phone" style="margin-top: 1px;"/>
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
                                    <th>Badge ID</th>
                                    <th>Sub Company</th>
                                    <th>English Name</th>
                                    <th>Arabic Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Image</th>
                                    <th>Operations</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->badge_id}}</td>
                                        <td>{{$user->sub_company->en_name}}</td>
                                        <td>{{$user->en_name}}</td>
                                        <td>{{$user->ar_name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->phone}}</td>
                                        <td>
                                            <img src="/companies/users/{{$user->image}}" class="image_radius"/>
                                        </td>

                                            <td>
                                                @if(company()->hasPermissionTo('users_observe'))
                                                    <a title="View User" href="/company/user/{{$user->id}}/view"><button class="btn btn-info btn-condensed"><i class="fa fa-eye"></i></button></a>
                                                @endif
                                                @if(company()->hasPermissionTo('users_operate'))
                                                    <a title="View Orders" href="/company/user/{{$user->id}}/orders/request"><button class="btn btn-success btn-condensed"><i class="fa fa-file-excel-o"></i></button></a>
                                                    <a title="Make Order" href="/company/user/{{$user->id}}/order/create"><button class="btn btn-info btn-condensed"><i class="fa fa-mail-forward"></i></button></a>
                                                    <a title="Edit User" href="/company/user/{{$user->id}}/edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                                    @if($user->active == 1)
                                                        <button class="btn btn-primary btn-condensed mb-control" data-box="#message-box-suspend-{{$user->id}}" title="Suspend"><i class="fa fa-minus-square"></i></button>
                                                    @else
                                                        <button class="btn btn-success btn-condensed mb-control" data-box="#message-box-activate-{{$user->id}}" title="Activate"><i class="fa fa-check-square"></i></button>
                                                    @endif
                                                @endif
                                            </td>

                                    </tr>

                                    <!-- activate with sound -->
                                    <div class="message-box message-box-success animated fadeIn" data-sound="alert/fail" id="message-box-activate-{{$user->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to activate a user,it will now be available for orders and app usage.</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/company/user/change_state" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="user_id" value="{{$user->id}}">
                                                        <input type="hidden" name="state" value="1">
                                                        <button type="submit" class="btn btn-success btn-lg pull-right">Activate</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end activate with sound -->

                                    <!-- suspend with sound -->
                                    <div class="message-box message-box-primary animated fadeIn" data-sound="alert/fail" id="message-box-suspend-{{$user->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to suspend a user,and the technician wont be available for orders nor app usage .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/company/user/change_state" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="user_id" value="{{$user->id}}">
                                                        <input type="hidden" name="state" value="0">
                                                        <button type="submit" class="btn btn-primary btn-lg pull-right">Suspend</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end suspend with sound -->
                                @endforeach

                                </tbody>
                            </table>
                            {{$users->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready( function() {
            $('#table').dataTable( {
                "iDisplayLength": 50
            } );
        } )
    </script>
@endsection

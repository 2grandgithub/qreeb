@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li>Users</li>
        @if(Request::is('admin/users/active'))
            <li class="active">Active</li>
        @else
            <li class="active">Suspended</li>
        @endif
    </ul>
    <!-- END BREADCRUMB -->

    <style>
        .logo
        {
            height: 50px;
            width: 50px;
            border: 1px solid #29B2E1;
            border-radius: 100px;
            box-shadow: 2px 2px 2px darkcyan;
        }
    </style>

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
            @include('admin.layouts.message')
            <!-- START BASIC TABLE SAMPLE -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="/admin/user/create"><button type="button" class="btn btn-info"> Add a new user </button></a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Image</th>
                                    <th>Operations</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->phone}}<br/>
                                        </td>
                                        <td>
                                            <img src="/users/{{$user->image}}" class="logo"/>
                                        </td>
                                        <td>
                                            <a title="View Image" href="/admin/user/{{$user->id}}/view"><button class="btn btn-info btn-condensed"><i class="fa fa-eye"></i></button></a>
                                            @if($user->active == 1)
                                                <button class="btn btn-primary btn-condensed mb-control" data-box="#message-box-suspend-{{$user->id}}" title="Suspend"><i class="fa fa-minus-square"></i></button>
                                            @else
                                                <button class="btn btn-success btn-condensed mb-control" data-box="#message-box-activate-{{$user->id}}" title="Activate"><i class="fa fa-check-square"></i></button>
                                            @endif
                                            <button class="btn btn-danger btn-condensed mb-control" data-box="#message-box-warning-{{$user->id}}" title="Delete"><i class="fa fa-trash-o"></i></button>
                                        </td>
                                    </tr>
                                    <!-- activate with sound -->
                                    <div class="message-box message-box-success animated fadeIn" data-sound="alert/fail" id="message-box-activate-{{$user->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to activate a user,it will now be available to log in .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/user/change_state" class="buttons">
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
                                                    <p>Your are about to suspend a user,and the provider wont be available for login .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/user/change_state" class="buttons">
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

                                    <!-- danger with sound -->
                                    <div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$user->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to delete a user,and you won't be able to restore its data again like orders under this user .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/user/delete" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="user_id" value="{{$user->id}}">
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
                            {{$users->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

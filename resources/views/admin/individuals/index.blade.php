@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li>Individuals</li>
        @if(Request::is('admin/individuals/active'))
            <li class="active">Active</li>
        @else
            <li class="active">Suspended</li>
        @endif
    </ul>
    <!-- END BREADCRUMB -->

    <style>
        .image
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
                        <a href="/admin/individual/create"><button type="button" class="btn btn-info"> Add a new individual </button></a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>English Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>logo</th>
                                    <th>Operations</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($techs as $tech)
                                    <tr>
{{--                                        <td>{{$tech->badge_id}}</td>--}}
                                        <td>{{$tech->category->parent->en_name}} - {{$tech->category->en_name}}</td>
                                        <td>{{$tech->en_name}}</td>
                                        <td>{{$tech->email}}</td>
                                        <td>{{$tech->phone}}</td>
                                        <td>
                                            <img src="/individuals/{{$tech->image}}" class="image_radius"/>
                                        </td>
                                        <td>
                                            <a title="View admin" href="/admin/individual/{{$tech->id}}/view"><button class="btn btn-info btn-condensed"><i class="fa fa-eye"></i></button></a>
                                            <a title="View admin" href="/admin/individual/{{$tech->id}}/edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                            @if($tech->active == 1)
                                                <button class="btn btn-primary btn-condensed mb-control" data-box="#message-box-suspend-{{$tech->id}}" title="Suspend"><i class="fa fa-minus-square"></i></button>
                                            @else
                                                <button class="btn btn-success btn-condensed mb-control" data-box="#message-box-activate-{{$tech->id}}" title="Activate"><i class="fa fa-check-square"></i></button>
                                            @endif
                                            <button class="btn btn-danger btn-condensed mb-control" data-box="#message-box-warning-{{$tech->id}}" title="Delete"><i class="fa fa-trash-o"></i></button>
                                        </td>
                                    </tr>

                                    <!-- activate with sound -->
                                    <div class="message-box message-box-success animated fadeIn" data-sound="alert/fail" id="message-box-activate-{{$tech->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to activate a individual,it will now be available for orders and search.</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/individual/change_state" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="tech_id" value="{{$tech->id}}">
                                                        <input type="hidden" name="state" value="1">
                                                        <button type="submit" class="btn btn-success btn-lg pull-right">Activate</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end activate with sound -->

                                    <!-- suspend with sound -->
                                    <div class="message-box message-box-primary animated fadeIn" data-sound="alert/fail" id="message-box-suspend-{{$tech->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to suspend a individual,and the individual wont be available for orders nor search .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/individual/change_state" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="tech_id" value="{{$tech->id}}">
                                                        <input type="hidden" name="state" value="0">
                                                        <button type="submit" class="btn btn-primary btn-lg pull-right">Suspend</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end suspend with sound -->

                                    <!-- danger with sound -->
                                    <div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$tech->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to delete a individual,and you won't be able to restore its data again like orders under this individual .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/individual/delete" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="tech_id" value="{{$tech->id}}">
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

                            {{--<!-- danger with sound -->--}}
                            {{--<div class="message-box message-box-danger animated fadeIn pop_delete" id="pop_delete" data-sound="alert/fail">--}}
                                {{--<div class="mb-container">--}}
                                    {{--<div class="mb-middle warning-msg alert-msg">--}}
                                        {{--<div class="mb-title"><span class="fa fa-times"></span>Alert !</div>--}}
                                        {{--<div class="mb-content">--}}
                                            {{--<p>Your are about to delete a individual,and you won't be able to restore its data again like orders under this individual .</p>--}}
                                            {{--<br/>--}}
                                            {{--<p>Are you sure ?</p>--}}
                                        {{--</div>--}}
                                        {{--<div class="mb-footer buttons">--}}
                                            {{--<button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>--}}
                                            {{--<form method="post" action="/admin/individual/delete" class="buttons">--}}
                                                {{--{{csrf_field()}}--}}
                                                {{--<input type="hidden" name="tech_id" id="delete" value="">--}}
                                                {{--<button type="submit" class="btn btn-danger btn-lg pull-right">Delete</button>--}}
                                            {{--</form>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<!-- end danger with sound -->--}}

                            {{$techs->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

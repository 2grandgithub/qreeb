@extends('admin.layouts.app')
@section('content')

    @php
        use App\Models\Collaboration;
    @endphp

    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li class="active">Collaborations</li>
    </ul>
    <!-- END BREADCRUMB -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
            @include('admin.layouts.message')
            <!-- START BASIC TABLE SAMPLE -->
                <div class="panel panel-default">
                    @if(admin()->hasPermissionTo('collaborations_operate'))
                        <div class="panel-heading">
                            <a href="/admin/collaboration/create"><button type="button" class="btn btn-info"> Add a new collaboration </button></a>
                        </div>
                    @endif
                    <form class="form-horizontal" method="get" action="/admin/collaborations/search">
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
                                    <th>Provider</th>
                                    <th>Companies</th>
                                    <th>Operations</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($colls as $provider_id => $collaborations)
                                    <tr>
                                        <td><a href="/admin/provider/{{Collaboration::get_provider($provider_id)->id}}/view"> {{Collaboration::get_provider($provider_id)->en_name}} </a></td>
                                        <td>

                                            @foreach($collaborations as $collaboration)
                                                <span>
                                                    <a href="/admin/compny/{{$collaboration->company->id}}/view"> {{$collaboration->company->en_name}} </a> - {{$collaboration->created_at->toDateString()}}
                                                </span>
                                                <br/>
                                            @endforeach
                                        </td>
                                        @if(admin()->hasPermissionTo('collaborations_operate'))
                                            <td>
                                                <a title="Edit" href="/admin/collaboration/{{$provider_id}}/edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                                <button class="btn btn-danger btn-condensed mb-control" data-box="#message-box-warning-{{$provider_id}}" title="Delete"><i class="fa fa-trash-o"></i></button>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- danger with sound -->
                                    <div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$provider_id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to delete a stack of collaborations between a provider and multiple companies .</p>
                                                    <br/>
                                                    <p>Are you sure ?</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                    <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                    <form method="post" action="/admin/collaboration/delete" class="buttons">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="provider_id" value="{{$provider_id}}">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

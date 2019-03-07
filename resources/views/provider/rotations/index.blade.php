@extends('provider.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li class="active">Rotations</li>
    </ul>
    <!-- END BREADCRUMB -->

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
            @include('provider.layouts.message')
            <!-- START BASIC TABLE SAMPLE -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="/provider/rotation/create"><button type="button" class="btn btn-info"> Add a new rotation </button></a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>English Name</th>
                                    <th>Arabic Name</th>
                                    <th>Technicians assigned</th>
                                    <th>From</th>
                                    <th>To</th>
                                    @if(provider()->hasPermissionTo('rotations_operate'))
                                        <th>Operations</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rotations as $rotation)
                                    <tr>
                                        <td>{{$rotation->en_name}}</td>
                                        <td>{{$rotation->ar_name}}</td>
                                        <td>{{$rotation->technicians->count()}}</td>
                                        <td>{{\Carbon\Carbon::parse($rotation->from)->format('g:i A')}}</td>
                                        <td>{{\Carbon\Carbon::parse($rotation->to)->format('g:i A')}}</td>
                                        @if(provider()->hasPermissionTo('rotations_operate'))
                                            <td>
                                                <a title="Edit" href="/provider/rotation/{{$rotation->id}}/edit"><button class="btn btn-warning btn-condensed"><i class="fa fa-edit"></i></button></a>
                                                <button class="btn btn-danger btn-condensed mb-control" data-box="#message-box-warning-{{$rotation->id}}" title="Delete"><i class="fa fa-trash-o"></i></button>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- danger with sound -->
                                    <div class="message-box message-box-danger animated fadeIn" data-sound="alert/fail" id="message-box-warning-{{$rotation->id}}">
                                        <div class="mb-container">
                                            <div class="mb-middle warning-msg alert-msg">
                                                <div class="mb-title"><span class="fa fa-times"></span>Alert !</div>
                                                <div class="mb-content">
                                                    <p>Your are about to delete a rotation,please choose another rotation to transfer the technicians to !</p>
                                                </div>
                                                <div class="mb-footer buttons">
                                                        <form method="post" action="/provider/rotation/delete" class="buttons">
                                                            {{csrf_field()}}


                                                                        <div class="input-group col-md-12">
                                                                            <select class="form-control select" name="alt_rotation_id">
                                                                                <option selected disabled>Select a rotation</option>
                                                                                @foreach($rotations->where('id','!=',$rotation->id) as $this_rotation)
                                                                                    <option value="{{$this_rotation->id}}">{{$this_rotation->en_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <br/>

                                                            <input type="hidden" name="rotation_id" value="{{$rotation->id}}">
                                                            <button class="btn btn-default btn-lg pull-right mb-control-close" style="margin-left: 5px;">Close</button>
                                                            <button type="submit" class="btn btn-danger btn-lg pull-right">Transfer & Delete</button>
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

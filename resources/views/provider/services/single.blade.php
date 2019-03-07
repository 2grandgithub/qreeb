@extends('provider.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/provider/dashboard">Dashboard</a></li>
        <li class="active">Update Services Fees</li>
    </ul>
    <!-- END BREADCRUMB -->
    @include('provider.layouts.message')
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="/provider/services/fees/update" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Update Services Fees
                            </h3>
                        </div>
                            <div class="panel-body">
                                <div class="row">
                                @foreach($cats as $key => $cat)
                                    <div class="col-md-3" >
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="row">
                                                    <label class="switch" >
                                                        <h3 class="panel-title" style="float: left;">{{$cat->en_name}}</h3>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <ul class="list-group border-bottom">
                                                    @foreach($cat->sub_cats as $sub)
                                                            <div class="col-md-12">
                                                                <div class="col-md-8" style="margin-bottom: 3px;">
                                                                    <li class="list-group-item">
                                                                        {{$sub->en_name}}
                                                                    </li>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input class="form-control" type="number" name="fees[][{{$sub->id}}]" value="{{$sub->cat_fee->fee}}" style="width: 80px;" required>
                                                                </div>
                                                            </div>
                                                    @endforeach

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Reset</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li><a href="/admin/providers/active">Companies</a></li>
        <li>{{$provider->en_name}}</li>
        <li class="active">Subscriptions</li>
    </ul>
    <!-- END BREADCRUMB -->
    {{--{{dd($errors)}}--}}
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="/admin/provider/subscriptions">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                {{isset($provider->subscriptions) ? 'Update subscriptions' : 'Set subscriptions'}}
                            </h3>
                        </div>
                        <div class="panel-body">
                                <div class="row">
                                        @foreach($cats as $key => $cat)

                                            @php
                                                //$x = is_int($key/4);
                                                $v = $cat->sub_cats->pluck('id')->toArray();
                                                $common = array_intersect($v,$subs);
                                            @endphp

                                            {{--@if($x  == true && $key != 0)--}}
                                            {{--</div>--}}
                                            {{--@endif--}}

                                            <div class="col-md-3" >
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <div class="row">
                                                            <label class="switch" >
                                                                <h3 class="panel-title" style="float: left ">{{$cat->en_name}}</h3>
                                                                <input style="float: right" type="checkbox" id="{{$cat->id}}" onclick="trigger({{$cat->id}});" @if($common == $v) checked @endif/>
                                                                <span style="float: right"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <ul class="list-group border-bottom">
                                                            @foreach($cat->sub_cats as $sub)
                                                                    <li class="list-group-item">
                                                                        {{$sub->en_name}}
                                                                        <label class="switch switch-small" style="float: right;margin: 0px 0px 4px;">
                                                                            <input type="checkbox" name="subs[]" onclick="child({{$sub->parent->id}});" @if(in_array($sub->id, $subs)) checked @endif value="{{$sub->id}}" class="{{$sub->parent_id}}"/>
                                                                            <span></span>
                                                                        </label>
                                                                    </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                        </div>
                        </div>


                            <input type="hidden" name="provider_id" value="{{$provider->id}}">


                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Reset</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                               Set
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    <script>
        function trigger(id)
        {
            var check = $('#'+id).is(':checked');

            if(check == true)
            {
                $("."+id).prop("checked", true);
            }
            else
            {
                $("."+id).prop("checked", false);
            }
        }


        function child(id)
        {
            var length = $('.'+id).length;
            var checked = $('.'+id+':checked').length;

            if(length == checked)
            {
                $('#'+id).prop("checked", true);
            }
            else
            {
                $("#"+id).prop("checked", false);
            }
        }

    </script>
@endsection

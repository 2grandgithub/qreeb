@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li> <a href="/admin/categories/all">Categories</a></li>
        <li class="active">{{isset($category) ? 'Update a category' : 'Create a category'}}</li>
    </ul>
    <!-- END BREADCRUMB -->

    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="{{isset($category) ? '/admin/category/main_update' : '/admin/category/main_store'}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                {{isset($category) ? 'Update an category' : 'Create an category'}}
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('en_name') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">English Name</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="en_name" @if(isset($category)) value="{{$category->en_name}}" @else value="{{old('en_name')}}" @endif/>
                                        <span class="input-group-addon"><span class="fa fa-cube"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'en_name'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Arabic Name</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="ar_name" @if(isset($category)) value="{{$category->ar_name}}" @else value="{{old('ar_name')}}" @endif/>
                                        <span class="input-group-addon"><span class="fa fa-cube"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'ar_name'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Image</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="file" class="fileinput btn-info" name="image" id="cp_photo" data-filename-placement="inside" title="Select Image"/>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'image'])
                                    <br/>
                                    @if(isset($category))
                                        <span class="label label-warning">Leave it there if no changes</span>
                                        <br/>
                                        <br/>
                                        <div>
                                            <img style="border : solid black 1px; width: 300px; height: 300px;" src="{{asset('categories/'.$category->image)}}" alt="{{$category->en_name}}"/>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" name="type" value="1">
                            @if(isset($category))
                                <input type="hidden" name="category_id" value="{{$category->id}}">
                            @endif
                        </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Reset</button> &nbsp;
                            <button class="btn btn-primary pull-right">
                                {{isset($category) ? 'Update' : 'Create'}}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection

@extends('admin.layouts.app')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="/admin/dashboard">Dashboard</a></li>
        <li> <a href="/admin/categories/all">Categories</a></li>
        <li class="active">{{isset($category) ? 'Update a category' : 'Create a category'}}</li>
    </ul>
    <!-- END BREADCRUMB -->
{{--    {{dd($errors)}}--}}
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="{{isset($category) ? '/admin/category/sec_update' : '/admin/category/sec_store'}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                {{isset($category) ? 'Update an category' : 'Create an category'}}
                            </h3>
                        </div>
                        <div class="panel-body">

                            <div class="form-group {{ $errors->has('parent_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Main Category</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control select" id="main_cats">
                                            <option selected disabled>Select Category</option>
                                            @forelse($categories as $cat)
                                                <option value="{{$cat->id}}">{{$cat->en_name}}</option>
                                            @empty
                                                <option selected disabled>Please add a category first in order to add sub categories .</option>
                                            @endforelse
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-cube"></span></span>
                                    </div>
                                    @if(isset($category))
                                    <span class="label label-warning">Leave it there if no changes</span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('parent_id') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Sub Category</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <select class="form-control selected" name="parent_id" id="sub_cats">
                                            <option selected disabled>Please choose a category first .</option>
                                        </select>
                                        <span class="input-group-addon"><span class="fa fa-cube"></span></span>
                                    </div>
                                    @if(isset($category))
                                        <span class="label label-warning">Leave it there if no changes</span>
                                    @endif
                                    @include('admin.layouts.error', ['input' => 'parent_id'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('en_name') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">English Name</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="en_name" @if(isset($category)) value="{{$category->en_name}}" @else value="{{old('en_name')}}" @endif required/>
                                        <span class="input-group-addon"><span class="fa fa-cube"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'en_name'])
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                <label class="col-md-3 col-xs-12 control-label">Arabic Name</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="ar_name" @if(isset($category)) value="{{$category->ar_name}}" @else value="{{old('ar_name')}}" @endif required/>
                                        <span class="input-group-addon"><span class="fa fa-cube"></span></span>
                                    </div>
                                    @include('admin.layouts.error', ['input' => 'ar_name'])
                                </div>
                            </div>

                            <input type="hidden" name="type" value="3">
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

    <script>
        $('#main_cats').on('change', function (e) {
            var parent_id = e.target.value;
            if (parent_id) {
                $.ajax({
                    url: '/admin/get_sub_cats/'+parent_id,
                    type: "GET",

                    dataType: "json",

                    success: function (data) {
                        $('#sub_cats').empty();
                        $('#sub_cats').append('<option selected disabled> Select a Sub Category </option>');
                        $.each(data, function (i, sub_cat) {
                            $('#sub_cats').append('<option value="' + sub_cat.id + '">' + sub_cat.en_name + '</option>');
                        });
                    }
                });
            }
        });
    </script>
@endsection

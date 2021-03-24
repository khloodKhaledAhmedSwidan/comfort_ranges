@extends('admin.layouts.master')

@section('title')
    @lang('messages.edit_service')
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">

@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/admin/home">@lang('messages.dashboard')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/admin/categories">@lang('messages.Services')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span> @lang('messages.edit_service')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.Services')
        <small> @lang('messages.edit_service')</small>
    </h1>
@endsection

@section('content')


    @if (session('information'))
        <div class="alert alert-success">
            {{ session('information') }}
        </div>
    @endif
    @if (session('pass'))
        <div class="alert alert-success">
            {{ session('pass') }}
        </div>
    @endif
    @if (session('privacy'))
        <div class="alert alert-success">
            {{ session('privacy') }}
        </div>
    @endif
    @if(count($errors))
        <ul class="alert alert-danger">
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @endif
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">

            <!-- BEGIN PROFILE CONTENT -->
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <div class="portlet-title tabbable-line">
                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span class="caption-subject font-blue-madison bold uppercase"> @lang('messages.edit_service')</span>
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="tab-content">
                                    <!-- PERSONAL INFO TAB -->

                                        <form role="form" action="{{route('categories.update',$category->id)}}" method="post" enctype="multipart/form-data">
                                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                            @method('put')
                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.service_name')</label>
                                                <input type="text" name="name_ar" placeholder="@lang('messages.service_name')" class="form-control" value="{{$category->name_ar}}" />
                                                @if ($errors->has('name_ar'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name_ar') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"> @lang('messages.service_name_en')</label>
                                                <input type="text" name="name" placeholder=" @lang('messages.service_name_en')" class="form-control" value="{{$category->name}}" />
                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"> @lang('messages.arranging')</label>
                                                <input type="number" name="arranging"
                                                       placeholder=" @lang('messages.arranging')"
                                                       class="form-control" value="{{$category->arranging}}"/>
                                                @if ($errors->has('arranging'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('arranging') }}</strong>
                                                    </span>
                                                @endif
                                            </div>


                                            <div class="form-group">
                                                <label>@lang('messages.choose_branch')</label>

                                                <select  class="form-control" name="branch_id">
                                                    <option value="{{\App\Models\Branch::find($category->branch_id)->id}}"
                                                            selected>{{app()->getLocale() =='en'?\App\Models\Branch::find($category->branch_id)->name:\App\Models\Branch::find($category->branch_id)->name_ar}}</option>
                                                    @foreach(\App\Models\Branch::all() as $branch)
                                                        @if (\App\Models\Branch::find($category->branch_id)->id != $branch->id)
                                                            <option value="{{ $branch->id }}" >{{app()->getLocale() =='en'?$branch->name:$branch->name_ar}}</option>

                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('branch_id')
                                                <span class="invalid-feedback" role="alert">
                        <strong style="color:red;">{{ $message }}</strong>
                    </span>
                                                @enderror

                                            </div>

                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3">@lang('messages.image')</label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail"
                                                                 data-trigger="fileinput"
                                                                 style="width: 200px; height: 150px;">
                                                                <img src="{{asset('uploads/categories/'.$category->image)}}"
                                                                     alt="">
                                                            </div>
                                                            <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> @lang('messages.choose_image') </span>
                                                                <span class="fileinput-exists">@lang('messages.change') </span>
                                                                <input type="file" name="image"> </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists"
                                                                   data-dismiss="fileinput">@lang('messages.remove') </a>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('image'))
                                                            <span class="help-block">
                                                        <strong style="color: red;">{{ $errors->first('image') }}
                                                        </strong>
                                                    </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="margiv-top-10">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn green">@lang('messages.save')</button>

                                                </div>
                                            </div>
                                        </form>

                                    <!-- END PERSONAL INFO TAB -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>

@endsection

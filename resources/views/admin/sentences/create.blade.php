@extends('admin.layouts.master')

@section('title')
@lang('messages.add_evaluation')
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
                <a href="{{url('/admin/sentences')}}">@lang('messages.evaluation')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.add_evaluation')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.evaluation')
        <small>@lang('messages.add_evaluation')</small>
    </h1>
@endsection

@section('content')



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
                                    <span class="caption-subject font-blue-madison bold uppercase">@lang('messages.add_evaluation')</span>
                                </div>

                            </div>
                            <form role="form" action="{{route('sentences.store')}}" method="post" enctype="multipart/form-data">
                                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->


                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.evaluation_singular')</label>
                                                <input type="text" name="sentence_ar" placeholder=" @lang('messages.evaluation_singular')" class="form-control" value="{{old('sentence_ar')}}" />
                                                @if ($errors->has('sentence_ar'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('sentence_ar') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        <div class="form-group">
                                                <label class="control-label"> @lang('messages.evaluation_singular_en')</label>
                                                <input type="text" name="sentence" placeholder=" @lang('messages.evaluation_singular_en')" class="form-control" value="{{old('sentence')}}" />
                                                @if ($errors->has('sentence'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('sentence') }}</strong>
                                                    </span>
                                                @endif
                                            </div>



                                        <!-- END PRIVACY SETTINGS TAB -->
                                    </div>

                                </div>
                                <div class="margiv-top-10">
                                    <div class="form-actions">
                                        <button type="submit" class="btn green" value="حفظ" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">@lang('messages.save')</button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
    </div>

@endsection



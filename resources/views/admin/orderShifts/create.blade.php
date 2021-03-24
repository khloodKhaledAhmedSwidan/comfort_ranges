@extends('admin.layouts.master')

@section('title')
@lang('messages.add_period')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <style>
        #map {
            height: 500px;
            width: 500px;
        }
    </style>
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/admin/home">@lang('messages.dashboard')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/orderShifts')}}">  @lang('messages.Periods_available_for_requests')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.add_period')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.Periods')
        <small>@lang('messages.add_period')</small>
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
                                    <span class="caption-subject font-blue-madison bold uppercase">@lang('messages.add_period')</span>
                                </div>

                            </div>
                            <form role="form" action="{{route('orderShifts.store')}}" method="post"
                                  enctype="multipart/form-data">
                                <input type='hidden' name='_token' value='{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.from')</label>

                                            <input type="time"
                                                   class="form-control"
                                                   placeholder="@lang('messages.from')  "
                                                   name="from"
                                                   value="{{old('from')}}">
                                        </div>
                                        <div class="form-group">

                                            <label class="control-label"> @lang('messages.to')</label>

                                                <input type="time" class="form-control"
                                                       placeholder="@lang('messages.to')   "
                                                       name="to" value="{{old('to')}}">


                                        </div>


                                        <!-- END PRIVACY SETTINGS TAB -->
                                    </div>

                                </div>
                                <div class="margiv-top-10">
                                    <div class="form-actions">
                                        <button type="submit" class="btn green" value="حفظ"
                                                onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">
                                            @lang('messages.save')
                                        </button>

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




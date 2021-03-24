@extends('admin.layouts.master')

@section('title')
    @lang('messages.addRejectedDate')
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
                <a href="/admin/home">@lang('messages.dashboard') </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/rejected_dates')}}">@lang('messages.rejected_dates')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>    @lang('messages.addRejectedDate')
</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.rejected_dates')
        <small>    @lang('messages.addRejectedDate')
        </small>
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
                                    <span class="caption-subject font-blue-madison bold uppercase">    @lang('messages.addRejectedDate')
</span>
                                </div>

                            </div>
                            <form role="form" action="{{route('rejected_dates.store')}}" method="post" enctype="multipart/form-data">
                                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->


                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.date')</label>
                                                <input type="date" name="date" placeholder="@lang('messages.date')" class="form-control" value="{{old('date')}}" />
                                                @if ($errors->has('date'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('date') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        <div class="form-group">
                                                <label class="control-label"> @lang('messages.dateReason')</label>
                                                <input type="text" name="reason" placeholder="  @lang('messages.dateReason')" class="form-control" value="{{old('reason')}}" />
                                                @if ($errors->has('reason'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('reason') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.reason_en')</label>
                                            <input type="text" name="reason_en"
                                                   placeholder="  @lang('messages.reason_en')" class="form-control"
                                                   value="{{old('reason_en')}}"/>
                                            @if ($errors->has('reason_en'))
                                                <span class="help-block">
                                                       <strong
                                                           style="color: red;">{{ $errors->first('reason_en') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
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



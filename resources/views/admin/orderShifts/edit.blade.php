@extends('admin.layouts.master')

@section('title')
@lang('messages.edit_period')
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
                <a href="/admin/orderShifts">@lang('messages.Periods')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.edit_period')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.Periods')
        <small>@lang('messages.edit_period')</small>
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
                                    <span class="caption-subject font-blue-madison bold uppercase">@lang('messages.edit_period')</span>
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="tab-content">
                                    <!-- PERSONAL INFO TAB -->

                                        <form role="form" action="{{route('orderShifts.update',$orderShift->id)}}" method="post" enctype="multipart/form-data">
                                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                            @method('put')

                                            <div class="form-group">
                                                <label class="control-label"> @lang('messages.from')</label>

                                                <input type="time"
                                                       class="form-control"
                                                       placeholder="@lang('messages.from')  "
                                                       name="from"
                                                       value="{{$orderShift->from}}">
                                            </div>
                                            <div class="form-group">

                                                <label class="control-label"> @lang('messages.to')</label>

                                                <input type="time" class="form-control"
                                                       placeholder=" @lang('messages.to')  "
                                                       name="to" value="{{$orderShift->to}}">


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

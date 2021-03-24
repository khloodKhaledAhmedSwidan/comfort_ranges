@extends('admin.layouts.master')

@section('title')
    @lang('messages.manager')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{ url('admin/home') }}">@lang('messages.dashboard')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('admins.index') }}"> @lang('messages.manager')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.show_personal_page')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">@lang('messages.show_personal_page')
        <small>@lang('messages.edit_personal_page')</small>
    </h1>
@endsection

@section('content')

    @if(session()->has('msg'))

        <p class="alert alert-success" style="width: 100%">

            {{ session()->get('msg') }}

        </p>
    @endif

    <form class="form-horizontal" method="post" action="{{ url('/admin/profileEdit') }}" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-lg-8">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="icon-settings font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> @lang('messages.Main_data')</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="btn-group"></div>



                        <div class="form-group">
                            <label for="username" class="col-lg-3 control-label">@lang('messages.name')</label>
                            <div class="col-lg-9">
                                <input id="username" name="name" type="text" value="{{ $data->name }}" class="form-control" placeholder="@lang('messages.name')">
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-lg-3 control-label">@lang('messages.email')</label>
                            <div class="col-lg-9">
                                <input id="email" name="email" type="email" value="{{ $data->email }}" class="form-control" placeholder="@lang('messages.email')">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="col-lg-3 control-label">@lang('messages.phone_number')</label>
                            <div class="col-lg-9">
                                <input id="phone" name="phone" type="text" value="{{ $data->phone }}" class="form-control" placeholder="@lang('messages.phone_number')">
                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div style="clear: both"></div>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-lg-2 col-lg-offset-10">
                                    {{--<button type="submit" class="btn green btn-block">حفظ</button>--}}
                                    <input class="btn green btn-block" type="submit" value="@lang('messages.save')" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    {{--{!! Form::close() !!}--}}
@endsection

@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
@endsection


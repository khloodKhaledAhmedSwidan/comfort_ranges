@extends('admin.layouts.master')

@section('title')
@lang('messages.edit_Privacy')
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
                <a href="/admin/roles">@lang('messages.Privacy')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.edit_Privacy')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.Privacy')
        <small>@lang('messages.edit_Privacy')</small>
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
                        <form role="form" action="{{route('roles.update',$role)}}" method="post"
                            enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>
                            @method('put')
                            <div class="portlet-body">

                                <div class="tab-content">
                                    <!-- PERSONAL INFO TAB -->
                                    <div class="tab-pane active" id="tab_1_1">

                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.name_of_privacy') </label>
                                            <input type="text" name="name_ar" class="form-control"
                                                   placeholder="@lang('messages.name_of_privacy')" value="{{$role->name_ar}}"/>
                                            @if ($errors->has('name_ar'))
                                                <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('name_ar') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.name_of_privacy_in_en')</label>
                                            <input type="text" name="name" class="form-control"
                                                   placeholder="@lang('messages.name_of_privacy_in_en')" value="{{$role->name}}"/>
                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">@lang('messages.Display_name')</label>
                                            <input type="text" name="display_name_ar" class="form-control"
                                                   placeholder="@lang('messages.Display_name')" value="{{$role->display_name_ar}}"/>
                                            @if ($errors->has('display_name_ar'))
                                                <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('display_name_ar') }}</strong>
                                            </span>
                                            @endif
                                        </div>   <div class="form-group">
                                            <label class="control-label"> @lang('messages.Display_name_in_english') </label>
                                            <input type="text" name="display_name" class="form-control"
                                                   placeholder=" @lang('messages.Display_name_in_english')" value="{{$role->display_name}}"/>
                                            @if ($errors->has('display_name'))
                                                <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('display_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.description') </label>
                                            <input type="text" name="description_ar" class="form-control"
                                                   placeholder="@lang('messages.description') " value="{{$role->description_ar}}"/>
                                            @if ($errors->has('description_ar'))
                                                <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('description_ar') }}</strong>
                                            </span>
                                            @endif
                                        </div>  <div class="form-group">
                                            <label class="control-label">@lang('messages.description_in_en')</label>
                                            <input type="text" name="description" class="form-control"
                                                   placeholder="@lang('messages.description_in_en') " value="{{$role->description}}"/>
                                            @if ($errors->has('description'))
                                                <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('description') }}</strong>
                                            </span>
                                            @endif
                                        </div>
{{--                                        <div class="form-group">--}}

{{--                                            {!! Form::label('permission_list','permission') !!}--}}
{{--                                            <br>--}}
{{--                                            <input id="selectAll" type="checkbox">--}}
{{--                                            <label for='selectAll'>Select All</label>--}}

{{--                                            <br>--}}
{{--                                            @foreach($permission as $Permission)--}}

{{--                                                <label class="checkbox-inline col-sm-3">--}}
{{--                                                    <input type="checkbox"  name="permission_list[]" value="{{$Permission->id}}"> {{$Permission->display_name}}--}}
{{--                                                </label>--}}
{{--                                            @endforeach--}}

{{--                                        </div>--}}
{{--                                        {{dd($permission)}}--}}
                                        @if(app()->getLocale() == 'en')
                                            <div class="form-group">
                                                {!! Form::label('permission_list','permission') !!}

                                                {!!  Form::select('permission_list[]',
                                 \App\Models\Permission::pluck('display_name','id') ,
                                        $permission,
                                                ['class'=> 'form-control select2','placeholder'=>'اختر قيم','multiple'=>'true']) !!}
                                                @error('display_name')
                                                <span class="invalid-feedback" role="alert">
                        <strong style="color:red;">{{ $message }}</strong>
                    </span>
                                                @enderror
                                            </div>
                                        @else
                                            <div class="form-group">
                                                {!! Form::label('permission_list','permission') !!}

                                                {!!  Form::select('permission_list[]',
                                 \App\Models\Permission::pluck('display_name_ar','id') ,
                                        $permission,
                                                ['class'=> 'form-control select2','placeholder'=>'اختر قيم','multiple'=>'true']) !!}
                                                @error('display_name_ar')
                                                <span class="invalid-feedback" role="alert">
                        <strong style="color:red;">{{ $message }}</strong>
                    </span>
                                                @enderror
                                            </div>
                                            @endif

                                    </div>
                                </div>
                            </div>
                            <div class="margiv-top-10">
                                <div class="form-actions">
                                    <button type="submit" class="btn green" value="حفظ"
                                        onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">@lang('messages.save')</button>
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
@section('scripts')
<script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
<script>

    $("#selectAll").click(function(){
        $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
    });

</script>
@endsection

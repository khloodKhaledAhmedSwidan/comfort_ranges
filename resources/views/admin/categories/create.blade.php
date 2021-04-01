@extends('admin.layouts.master')

@section('title')
    @lang('messages.add_service')
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
                <a href="{{url('/admin/categories')}}">@lang('messages.Services')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>    @lang('messages.add_service')
</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.Services')
        <small>    @lang('messages.add_service')
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
                                    <span class="caption-subject font-blue-madison bold uppercase">    @lang('messages.add_service')
</span>
                                </div>

                            </div>
                            <form role="form" action="{{route('categories.store')}}" method="post" enctype="multipart/form-data">
                                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->


                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.service_name')</label>
                                                <input type="text" name="name_ar" placeholder="@lang('messages.service_name')" class="form-control" value="{{old('name_ar')}}" />
                                                @if ($errors->has('name_ar'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name_ar') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        <div class="form-group">
                                                <label class="control-label"> @lang('messages.service_name_en')</label>
                                                <input type="text" name="name" placeholder="  @lang('messages.service_name_en')" class="form-control" value="{{old('name')}}" />
                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        <div class="form-group">
                                            <label class="control-label"> @lang('messages.arranging')</label>
                                            <input type="number" name="arranging"
                                                   placeholder="  @lang('messages.arranging')"
                                                   class="form-control" value="{{old('arranging')}}"/>
                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('arranging') }}</strong>
                                                    </span>
                                            @endif
                                        </div>


                                        <div class="form-group">
                                            <label>@lang('messages.choose_branch')</label>

                                                <select  class="form-control" name="branch_id">
                                                    <option value="" disabled
                                                            selected>@lang('messages.choose_branch') </option>
                                                    @foreach(\App\Models\Branch::all() as $branch)
                                                        @if (\Illuminate\Support\Facades\Input::old('branch_id') == $branch->id)
                                                            <option value="{{ $branch->id }}" selected>{{app()->getLocale() =='en'?$branch->name:$branch->name_ar}}</option>
                                                        @else
                                                            <option value="{{ $branch->id }}">{{app()->getLocale() =='en'?$branch->name:$branch->name_ar}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('branch_id')
                                                <span class="invalid-feedback" role="alert">
                        <strong style="color:red;">{{ $message }}</strong>
                    </span>
                                                @enderror

                                        </div>



                                        <div class="form-group">
                                            <label class="control-label">{{app()->getLocale() == 'en' ?'choose periods':'اختر الفترات'}}</label>
                                     
                                               <select name="order_shifts[]" class="form-control select2" required multiple>
                                               <option selected disabled > {{app()->getLocale() == 'en' ?'choose periods':'اختر الفترات'}}  </option>
                                               @foreach(App\Models\OrderShift::get() as $orderShift)
                                                  <option value="{{$orderShift->id}}"> {{app()->getLocale() == 'en'?'from'. ' '. \Carbon\Carbon::createFromFormat('H:i:s',$orderShift->from )->format('h:i') .' '.'to'.' '.\Carbon\Carbon::createFromFormat('H:i:s',$orderShift->to )->format('h:i'):'من ' .' ' .\Carbon\Carbon::createFromFormat('H:i:s',$orderShift->from )->format('h:i').' '.'الي'.' '.\Carbon\Carbon::createFromFormat('H:i:s',$orderShift->to )->format('h:i')}} </option>
                                               @endforeach
                                               </select>
                                                @if ($errors->has('order_shifts'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('order_shifts') }}</strong>
                                                    </span>
                                                @endif
                                           
                                        </div>

                                        <div class="form-body">
                                            <div class="form-group ">
                                                <label class="control-label col-md-3">@lang('messages.image') </label>
                                                <div class="col-md-9">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                        </div>
                                                        <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> @lang('messages.choose_image') </span>
                                                                <span class="fileinput-exists"> @lang('messages.change') </span>
                                                                <input type="file" name="image"> </span>
                                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('messages.remove') </a>



                                                        </div>
                                                    </div>
                                                    @if ($errors->has('image'))
                                                        <span class="help-block">
                                                               <strong style="color: red;">{{ $errors->first('image') }}</strong>
                                                            </span>
                                                    @endif
                                                </div>

                                            </div>
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



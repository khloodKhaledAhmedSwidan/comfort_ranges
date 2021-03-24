@extends('admin.layouts.master')

@section('title')
    @lang('messages.add_user')
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
                <a href="{{url('/admin/users')}}">@lang('messages.users')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span> @lang('messages.add_user')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.users')
        <small> @lang('messages.add_user')</small>
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
                                    <span
                                        class="caption-subject font-blue-madison bold uppercase">@lang('messages.Profile_account')</span>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1_1" data-toggle="tab">@lang('messages.personal_information')</a>
                                    </li>

                                    <li>
                                        <a href="#tab_1_4" data-toggle="tab">@lang('messages.Privacy_settings')</a>
                                    </li>
                                </ul>
                            </div>
                            <form role="form" action="/admin/add/user" method="post" enctype="multipart/form-data">
                                <input type='hidden' name='_token' value='{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="tab-pane active" id="tab_1_1">


                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.name')</label>
                                                <input type="text" name="name" placeholder="@lang('messages.name')" class="form-control"
                                                       value="{{old('name')}}"/>
                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.phone_number')</label>

                                                <input type="number" name="phone" placeholder="@lang('messages.phone_number')"
                                                       class="form-control" value="{{old('phone')}}"/>
                                                @if ($errors->has('phone'))
                                                    <span class="help-block">
                                                       <strong
                                                           style="color: red;">{{ $errors->first('phone') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.password')</label>
                                                <input type="password" name="password" class="form-control"/>
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                       <strong
                                                           style="color: red;">{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.password_confirmation')</label>
                                                <input type="password" name="password_confirmation"
                                                       class="form-control"/>
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="help-block">
                                                       <strong
                                                           style="color: red;">{{ $errors->first('password_confirmation') }}</strong>
                                                    </span>
                                                @endif
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.branches')</label>
                                                <div class="total" id="branch-selection">
                                                    <select class="form-control" name="branch_id" required>
                                                        <option selected disabled>@lang('messages.choose_branch')</option>
                                                        @foreach (\App\Models\Branch::all() as $branch)

                                                            <option
                                                                value="{{$branch->id}}"> {{app()->getLocale()  =='en'?$branch->name:$branch->name_ar}} </option>


                                                        @endforeach
                                                    </select>

                                                    @error('branch_id')
                                                    <span class="help-block">
                            <strong style="color: red;">
                                {{ $errors->first('branch_id') }}
                            </strong>
                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.available_orders')</label>
                                                <input type="number"  value="{{old('available_orders')}}" name="available_orders"
                                                       class="form-control" />
                                                @if ($errors->has('available_orders'))
                                                    <span class="help-block">
                                                       <strong
                                                           style="color: red;">{{ $errors->first('available_orders') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">

                                                <label for="name">@lang('messages.choose_service')</label>

                                                <div class="total" id="category-selection">

                                                    {{-- <span> @lang('messages.choose-branch') : </span> --}}

                                                    <select class="form-control select2" name="category_id[]" required multiple>

                                                        <option selected
                                                                disabled>@lang('messages.choose_service') </option>

                                                    </select>

                                                    @error('category_id')

                                                    <span class="help-block">

                                <strong style="color: red;">

                                    {{ $errors->first('category_id') }}

                                </strong>

                            </span>

                                                    @enderror

                                                </div>


                                            </div>



                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3">@lang('messages.image')</label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail"
                                                                 data-trigger="fileinput"
                                                                 style="width: 200px; height: 150px;">
                                                            </div>
                                                            <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> @lang('messages.choose_image') </span>
                                                                <span class="fileinput-exists"> @lang('messages.change') </span>
                                                                <input type="file" name="image"> </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists"
                                                                   data-dismiss="fileinput"> @lang('messages.remove') </a>


                                                            </div>
                                                        </div>
                                                        @if ($errors->has('image'))
                                                            <span class="help-block">
                                                               <strong
                                                                   style="color: red;">{{ $errors->first('image') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <!-- END PERSONAL INFO TAB -->


                                        <!-- PRIVACY SETTINGS TAB -->
                                        <div class="tab-pane" id="tab_1_4">

                                            <table class="table table-light table-hover">

                                                <tr>
                                                    <td> @lang('messages.active_customer')</td>
                                                    <td>
                                                        <div class="mt-radio-inline">
                                                            <label class="mt-radio">
                                                                <input type="radio" name="active"
                                                                       value="1" {{ old('active') == "1" ? 'checked' : '' }}/>
                                                                @lang('messages.yes_active')
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-radio">
                                                                <input type="radio" name="active"
                                                                       value="0" {{ old('active') == "0" ? 'checked' : '' }}/>
                                                               @lang('messages.no_active')
                                                                <span></span>
                                                            </label>
                                                            @if ($errors->has('active'))
                                                                <span class="help-block">
                                                                       <strong
                                                                           style="color: red;">{{ $errors->first('active') }}</strong>
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>


                                            </table>


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
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
    <script>

        $(document).ready(function () {

            $('select[name="branch_id"]').on('change', function () {

                var branch_id = $(this).val();
                if (branch_id) {

                    $.ajax({

                        url: '/admin/get_categories/' + branch_id,

                        type: 'GET',

                        datatype: 'json',

                        success: function (data) {
                            console.log(data)
                            $('select[name="category_id[]"]').empty();
                            $.each(data, function (key, value) {

                                $('select[name="category_id[]"]').append('<option value="' + value['id'] + '">' + value['name_ar'] + '</option>');

                            });

                        }

                    });

                } else {

                    $('select[name="branch_id"]').empty();


                }


            });


        });

    </script>



    <script>
        $(document).ready(function () {
            $('select[name="address[country]"]').on('change', function () {
                var id = $(this).val();
                $.ajax({
                    url: '/get/cities/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#register_city').empty();


                        $('select[name="address[city]"]').append('<option value>المدينة</option>');
                        // $('select[name="city"]').append('<option value>المدينة</option>');
                        $.each(data['cities'], function (index, cities) {

                            $('select[name="address[city]"]').append('<option value="' + cities.id + '">' + cities.name + '</option>');

                        });


                    }
                });


            });
        });
    </script>
@endsection

@extends('admin.layouts.master')

@section('title')
@lang('messages.edit_user')
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
                <a href="/admin/users">@lang('messages.users')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.edit_user')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.users')
        <small>@lang('messages.edit_user')</small>
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
                                    <span
                                        class="caption-subject font-blue-madison bold uppercase">@lang('messages.Profile_account')</span>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1_1" data-toggle="tab">@lang('messages.personal_information')</a>
                                    </li>

                                    <li>
                                        <a href="#tab_1_3" data-toggle="tab">@lang('messages.change_password')</a>
                                    </li>
                                    <li>
                                        <a href="#tab_1_4" data-toggle="tab">@lang('messages.Privacy_settings')</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="portlet-body">
                                <div class="tab-content">
                                    <!-- PERSONAL INFO TAB -->
                                    <div class="tab-pane active" id="tab_1_1">
                                        <form role="form" action="/admin/update/user/{{$user->id}}" method="post"
                                              enctype="multipart/form-data">
                                            <input type='hidden' name='_token' value='{{Session::token()}}'>
                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.name')</label>
                                                <input type="text" name="name" placeholder="@lang('messages.name')" class="form-control"
                                                       value="{{$user->name}}"/>
                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.phone_number')</label>
                                                <input type="number" name="phone" placeholder="  @lang('messages.phone_number')"
                                                       class="form-control" value="{{$user->phone}}"/>
                                                @if ($errors->has('phone'))
                                                    <span class="help-block">
                                                       <strong
                                                           style="color: red;">{{ $errors->first('phone') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.branches')</label>
                                                <div class="total" id="branch-selection">
                                                    <select class="form-control" name="branch_id" required>
                                                        <option selected disabled>@lang('messages.choose_branch')</option>
                                                        <option selected
                                                                value="{{$user->branch_id}}">{{app()->getLocale() == 'en'?$user->branch->name :$user->branch->name_ar}} </option>
                                                        @foreach (\App\Models\Branch::all() as $branch)
                                                            @if($branch->id != $user->branch_id   )
                                                                <option
                                                                    value="{{$branch->id}}"> {{app()->getLocale() == 'en'?$branch->name:$branch->name_ar}} </option>

                                                            @endif

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

                                                <label for="name">@lang('messages.choose_service')</label>

                                                <div class="total" id="category-selection">

                                                    {{-- <span> @lang('messages.choose-branch') : </span> --}}


                                                    <select class="form-control select2" name="category_id[]" required
                                                            multiple>

                                                        <option selected
                                                                disabled>@lang('messages.choose_service')
                                                        </option>

                                                        @foreach($user->branch->categories()->get() as $category)
{{-- '                                                        @php
                                                            $categoryUser = App\Models\CategoryUser::where('user_id',$user->id)->pluck('category_id')
                                                        @endphp
                                                            @if($category->whereIn($category->id,[ $categoryUser]))
                                                            <option selected
                                                                    value="{{$category->id}}"> {{app()->getLocale() == 'en'?$category->name:$category->name_ar}}
                                                            </option>
                                                            @else
                                                                <option
                                                                        value="{{$category->id}}"> {{app()->getLocale() == 'en'?$category->name:$category->name_ar}}
                                                                </option>
                                                            @endif --}}


@foreach($user->categories()->get() as $cat)
<option
value="{{$category->id}}" {{$category->id == $cat->id ? "selected" :''}}> {{app()->getLocale() == 'en'?$category->name:$category->name_ar}}
</option>
@endforeach

                                                        @endforeach


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

                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.available_orders')</label>
                                                <input type="number"  value="{{$user->available_orders}}" name="available_orders"
                                                       class="form-control" />
                                                @if ($errors->has('available_orders'))
                                                    <span class="help-block">
                                                       <strong
                                                           style="color: red;">{{ $errors->first('available_orders') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3"> @lang('messages.image')</label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail"
                                                                 data-trigger="fileinput"
                                                                 style="width: 200px; height: 150px;">
                                                                @if($user->image !==null)
                                                                    <img
                                                                        src='{{ asset("uploads/users/$user->image") }}'>
                                                                @endif
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
                                            <div class="margiv-top-10">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn green">@lang('messages.save')</button>

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- END PERSONAL INFO TAB -->

                                    <!-- CHANGE PASSWORD TAB -->
                                    <div class="tab-pane" id="tab_1_3">
                                        <form action="/admin/update/pass/{{$user->id}}" method="post">
                                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.new_password')</label>
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
                                            <div class="margin-top-10">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn green">@lang('messages.save')</button>

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- END CHANGE PASSWORD TAB -->
                                    <!-- PRIVACY SETTINGS TAB -->
                                    <div class="tab-pane" id="tab_1_4">
                                        <form action="/admin/update/privacy/{{$user->id}}" method="post">
                                            <input type='hidden' name='_token' value='{{Session::token()}}'>
                                            <table class="table table-light table-hover">

                                                <tr>
                                                    <td> @lang('messages.active_customer')</td>
                                                    <td>
                                                        <div class="mt-radio-inline">
                                                            <label class="mt-radio">
                                                                <input type="radio" name="active"
                                                                       value="1" {{ $user->active == "1" ? 'checked' : '' }}/>
                                                                @lang('messages.yes_active')
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-radio">
                                                                <input type="radio" name="active"
                                                                       value="0" {{$user->active == "0" ? 'checked' : '' }}/>
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
                                            <div class="margin-top-10">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn green">@lang('messages.save')</button>

                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                    <!-- END PRIVACY SETTINGS TAB -->
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
            // for get regions
            $('select[name="city_id"]').on('change', function () {
                var id = $(this).val();
                if (id) {
                    $.ajax({
                        url: '/admin/get/regions/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#choose_region').empty();
                            $('#places').empty();
                            $('#choose_city').empty();

                            $('select[name="c"]').append('<option value>اختر المنطقة</option>');
                            $.each(data['regions'], function (index, regions) {

                                $('select[name="region_id"]').append('<option value="' + regions.id + '">' + regions.name + '</option>');

                            });
                            $('select[name="places"]').append('<option value>اختر المنطقة</option>');
                            $.each(data['regions'], function (index, regions) {

                                $('select[name="places"]').append('<option value="' + regions.id + '">' + regions.name + '</option>');

                            });


                        }
                    });
                } else {
                    $('#choose_region').empty();
                    $('#places').empty();
                    $('#choose_city').empty();
                }
            });


            $("body").on("change", "input[type=radio][name=multi_place]", function () {
                // $( this ).after( "<p>Another paragraph! " + (++count) + "</p>" );

                all_payment_status = $(this).val();
                var id = $(this).val();
                if (id == "1") {

                    $('#multi_placce').show();


                } else {
                    $('#multi_placce').hide();


                }


            });
        });
    </script>
@endsection

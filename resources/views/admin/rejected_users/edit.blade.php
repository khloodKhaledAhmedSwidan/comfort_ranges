@extends('admin.layouts.master')

@section('title')
    @lang('messages.edit_rejected_users')
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

                <a href="{{url('/admin/rejected_users')}}">@lang('messages.rejected_users')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span> @lang('messages.edit_rejected_users')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.rejected_users')
        <small> @lang('messages.edit_rejected_users')</small>
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
                                    <span class="caption-subject font-blue-madison bold uppercase"> @lang('messages.edit_rejected_users')</span>
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="tab-content">
                                    <!-- PERSONAL INFO TAB -->

                                        <form role="form" action="{{route('rejected_users.update',$rejectUser->id)}}" method="post" enctype="multipart/form-data">
                                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                            @method('put')
                                        

                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.date')</label>
                                         
                                                   <select name="rejected_date_id" class="form-control select2" required >
                                                   <option selected disabled> @lang('messages.date')  </option>
                                                   @foreach(App\Models\RejectedDate::get() as $rejectedDate)

<option value="{{$rejectedDate->id}}" @if($rejectedDate->id == $rejectUser->rejected_date_id  ) selected @endif>{{$rejectedDate->reject_date}}  </option>




                                                   @endforeach
                                                   </select>
                                                    @if ($errors->has('rejected_date_id'))
                                                        <span class="help-block">
                                                           <strong style="color: red;">{{ $errors->first('rejected_date_id') }}</strong>
                                                        </span>
                                                    @endif
                                               
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">{{app()->getLocale() == 'en' ?'choose period':'اختر الفترة'}}</label>
                                         
                                                   <select name="order_shift_id" class="form-control select2" required >
                                                   <option selected disabled>{{app()->getLocale() == 'en' ?'choose period':'اختر الفترة'}}  </option>
                                                   @foreach(App\Models\OrderShift::get() as $orderShift)

<option value="{{$orderShift->id}}" @if($orderShift->id == $rejectUser->order_shift_id   ) selected @endif>{{app()->getLocale() == 'en'?'from'. ' '. \Carbon\Carbon::createFromFormat('H:i:s',$orderShift->from )->format('h:i') .' '.'to'.' '.\Carbon\Carbon::createFromFormat('H:i:s',$orderShift->to )->format('h:i'):'من ' .' ' .\Carbon\Carbon::createFromFormat('H:i:s',$orderShift->from )->format('h:i').' '.'الي'.' '.\Carbon\Carbon::createFromFormat('H:i:s',$orderShift->to )->format('h:i')}} </option>




                                                   @endforeach
                                                   </select>
                                                    @if ($errors->has('order_shift_id'))
                                                        <span class="help-block">
                                                           <strong style="color: red;">{{ $errors->first('order_shift_id') }}</strong>
                                                        </span>
                                                    @endif
                                               
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label">{{app()->getLocale() == 'en' ?'choose employees':'اختر الموظفيين'}}</label>
                                         
                                                   <select name="user_id[]" class="form-control select2" required multiple>
                                                   <option selected disabled> {{app()->getLocale() == 'en' ?'choose employees':'اختر الموظفيين'}}  </option>
                                                   @foreach(App\user::where('type','2')->where('active',1)->get() as $user)
@if($rejectUser->rejecteds()->where('user_id',$user->id)->first() != null)
<option value="{{$user->id}}" @if($user->id == $rejectUser->rejecteds()->where('user_id',$user->id)->first()->user_id ) selected @endif> {{$user->name}}  </option>

@else
<option value="{{$user->id}}"> {{$user->name}}  </option>

@endif


                                                   @endforeach
                                                   </select>
                                                    @if ($errors->has('user_id'))
                                                        <span class="help-block">
                                                           <strong style="color: red;">{{ $errors->first('user_id') }}</strong>
                                                        </span>
                                                    @endif
                                               
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

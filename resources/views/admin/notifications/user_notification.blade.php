@extends('admin.layouts.master')

@section('title')
@lang('messages.Send_notifications_to_users')
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
                <a href="{{url('/admin/general-notifications')}}">   @lang('messages.Send_notifications_to_users')
                </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.send_notification')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">   @lang('messages.Send_notifications_to_users')

        <small>@lang('messages.send_notification')</small>
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
                                    <span class="caption-subject font-blue-madison bold uppercase">@lang('messages.send_notification')</span>
                                </div>

                            </div>
                            <form method="post" action="{{route('notifications.user')}}" enctype="multipart/form-data" >
                                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                <div class="portlet light bordered table-responsive">
                                    <div class="portlet-body">
                                        <div class="row">
                                            <!-- BEGIN SAMPLE FORM PORTLET-->
                                            <div class="portlet light bordered table-responsive">
                                                <div class="portlet-body form">
                                                    <div class="form-horizontal" role="form">
                                                        <div class="form-body">
                                                            <!-- <div class="form-group">
                                                        <label class="col-md-3 control-label"> المدينة</label>
                                                        <div class="col-md-9">

                                                                </div>
                                                        </div> -->
{{-- 

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">@lang('messages.users')</label>
                                                                <div class="col-md-9">
                                                                    <select name="user_id"
                                                                            class="form-control ">
                                                                        <option value="" disabled
                                                                                selected>@lang('messages.users')
                                                                        </option>
                                                                        @foreach(\App\User::where('active',1)->get() as $user)
                                                                        <option value="{{$user->id}}">{{$user->name}}
                                                                        </option>
                                                                        @endforeach

                                                                    </select>
                                                                </div>
                                                            </div> --}}



                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">@lang('messages.users')</label>
                                                                <div class="col-md-9">
                                                                    {!! Form::select('user_id[]', App\User::where('active',1)->pluck('name','id'), null,
                                                                             ['class'=>'form-control select2','multiple']) !!}
                                                                    @if ($errors->has('user_id'))
                                                                        <span class="help-block">
                                                                            <strong style="color: red;">{{ $errors->first('user_id') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
            
            
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">@lang('messages.title')</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" name="title_ar" class="form-control" placeholder="@lang('messages.title') " value="{{old('title_ar')}}">
                                                                    @if ($errors->has('title_ar'))
                                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('title_ar') }}</strong>
                                            </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label"> @lang('messages.title_en')</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" name="title" class="form-control" placeholder="@lang('messages.title_en')" value="{{old('title')}}">
                                                                    @if ($errors->has('title'))
                                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('title') }}</strong>
                                            </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">@lang('messages.notification_content')</label>
                                                                <div class="col-md-9">
                                                                    <textarea  name="description_ar" class="form-control" placeholder="@lang('messages.notification_content') " value="{{old('description_ar')}}"></textarea>
                                                                    @if ($errors->has('description_ar'))
                                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('description_ar') }}</strong>
                                            </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">@lang('messages.notification_content_en')</label>
                                                                <div class="col-md-9">
                                                                    <textarea  name="description" class="form-control" placeholder="@lang('messages.notification_content_en')" value="{{old('description')}}"></textarea>
                                                                    @if ($errors->has('description'))
                                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('description') }}</strong>
                                            </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>







                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END SAMPLE FORM PORTLET-->


                                        </div>


                                        <!-- END CONTENT BODY -->

                                        <!-- END CONTENT -->


                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn green" value="حفظ" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">@lang('messages.save')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- END TAB PORTLET-->
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
    <script>

        function getLocation() {

            if (navigator.geolocation) {

                navigator.geolocation.getCurrentPosition(showPosition);

            } else {

                x.innerHTML = "Geolocation is not supported by this browser.";

            }

        }


        function showPosition(position) {

            lat = position.coords.latitude;

            lon = position.coords.longitude;


            document.getElementById('lat').value = lat; //latitude

            document.getElementById('lng').value = lon; //longitude

            latlon = new google.maps.LatLng(lat, lon)

            mapholder = document.getElementById('mapholder')

            //mapholder.style.height='250px';

            //mapholder.style.width='100%';


            var myOptions = {

                center: latlon,

                zoom: 14,

                mapTypeId: google.maps.MapTypeId.ROADMAP,

                mapTypeControl: false,

                navigationControlOptions: {

                    style: google.maps.NavigationControlStyle.SMALL

                }

            };

            var map = new google.maps.Map(document.getElementById("map"), myOptions);

            var marker = new google.maps.Marker({

                position: latlon,

                map: map,

                title: "You are here!"

            });

        }


    </script>


    <script type="text/javascript">

        var map;


        function initMap() {

            var latitude = 24.774265; // YOUR LATITUDE VALUE

            var longitude = 46.738586; // YOUR LONGITUDE VALUE

            var myLatLng = {

                lat: latitude,

                lng: longitude

            };

            map = new google.maps.Map(document.getElementById('map'), {

                center: myLatLng,

                zoom: 5,

                gestureHandling: 'true',

                zoomControl: false // disable the default map zoom on double click

            });


            var marker = new google.maps.Marker({

                position: myLatLng,

                map: map,

                //title: 'Hello World'


                // setting latitude & longitude as title of the marker

                // title is shown when you hover over the marker

                title: latitude + ', ' + longitude

            });


            //Listen for any clicks on the map.

            google.maps.event.addListener(map, 'click', function (event) {

                //Get the location that the user clicked.

                var clickedLocation = event.latLng;

                //If the marker hasn't been added.

                if (marker === false) {

                    //Create the marker.

                    marker = new google.maps.Marker({

                        position: clickedLocation,

                        map: map,

                        draggable: true //make it draggable

                    });

                    //Listen for drag events!

                    google.maps.event.addListener(marker, 'dragend', function (event) {

                        markerLocation();

                    });

                } else {

                    //Marker has already been added, so just change its location.

                    marker.setPosition(clickedLocation);

                }

                //Get the marker's location.

                markerLocation();

            });


            function markerLocation() {

                //Get location.

                var currentLocation = marker.getPosition();

                //Add lat and lng values to a field that we can save.

                document.getElementById('lat').value = currentLocation.lat(); //latitude

                document.getElementById('lng').value = currentLocation.lng(); //longitude

            }

        }


    </script>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFUMq5htfgLMNYvN4cuHvfGmhe8AwBeKU&callback=initMap" async
            defer></script>

@endsection



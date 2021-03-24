@extends('admin.layouts.master')

@section('title')
    @lang('messages.add_branch')
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
                <a href="{{url('/admin/branches')}}">@lang('messages.branches')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.add_branch')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> @lang('messages.branches')
        <small>@lang('messages.add_branch')</small>
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
                                    <span class="caption-subject font-blue-madison bold uppercase">@lang('messages.add_branch')</span>
                                </div>

                            </div>
                            <form role="form" action="{{route('branches.store')}}" method="post" enctype="multipart/form-data">
                                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->


                                            <div class="form-group">
                                                <label class="control-label">@lang('messages.name_of_branch')</label>
                                                <input type="text" name="name_ar" placeholder="@lang('messages.name_of_branch')" class="form-control" value="{{old('name_ar')}}" />
                                                @if ($errors->has('name_ar'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name_ar') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        <div class="form-group">
                                                <label class="control-label"> @lang('messages.name_of_branch_en')</label>
                                                <input type="text" name="name" placeholder=" @lang('messages.name_of_branch_en')" class="form-control" value="{{old('name')}}" />
                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>





                                        <div class=" form-group " id="hide-map">


                                            <div class="content sections">


                                                <div class="wrap-title d-flex justify-content-between mm">


                                                    <a onclick="getLocation();" > <i
                                                            class="btn btn-primary ">  @lang('messages.specify_location')</i>

                                                    </a>

                                                </div>


                                                <input type="number" placeholder="@lang('messages.latitude')" id="lat" name="latitude" class="form-control mb-2"
                                                       required/>

                                                @if ($errors->has('latitude'))

                                                    <span class="help-block">

                            <strong style="color: red;">{{ $errors->first('latitude') }}</strong>

                        </span>

                                                @endif

                                                <input type="number" placeholder="@lang('messages.longitude')" id="lng" name="longitude" class="form-control mb-2"
                                                    required/>

                                                @if ($errors->has('longitude'))

                                                    <span class="help-block">

                            <strong style="color: red;">{{ $errors->first('longitude') }}</strong>

                        </span>

                                                @endif

                                                <div id="map"></div>

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
@section('scripts')
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



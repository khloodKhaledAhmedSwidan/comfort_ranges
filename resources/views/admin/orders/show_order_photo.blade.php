@extends('admin.layouts.master')

@section('title')
@lang('messages.show_images')@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}" xmlns="http://www.w3.org/1999/html">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/admin/home')}}">@lang('messages.dashboard')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/show-images-of-order/'.$order->id)}}">  @lang('messages.show_images') </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.show_images') </span>
            </li>
        </ul>
    </div>

{{--    <h1 class="page-title">{{trans('messages.ads')}}--}}
{{--        <small>{{trans('messages.edit')}} {{trans('messages.ads')}}  </small>--}}
{{--    </h1>--}}
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
                                @include('flash::message')

                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span class="caption-subject font-blue-madison bold uppercase">    </span>
                                </div>
                            </div>
                            <form role="form" action="" method="post" enctype="multipart/form-data">
                                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="tab-pane active" id="tab_1_1">

                                            <div class="form-group">
                                                <label class="control-label">   @lang('messages.images')  </label>
                                                <div class="row">
                                                    @foreach($order->images()->get() as $img)
                                                        <div class="col-sm-3 img_{{ $img->id }}">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                                @if($img->image !==null)
                                                                    <img   src='{{ asset("uploads/orders/$img->image") }}' style="display: block !important;">
                                                                @endif
                                                            </div>

                                                        </div>
                                                    @endforeach
                                                </div>

                                            </div>
                                            <!-- END PERSONAL INFO TAB -->
                                        </div>

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
        $(".delete_image").click(function(){
            var id = $(this).attr('id');
            var url = '';

            url = url.replace(':id', id);

            //alert(image_id );
            $.ajax({
                url: url,
                type: 'GET',
                success: function(result) {
                    if (!result.message)
                    {
                        $(".img_"+id).fadeOut('1000');
                    }

                }
            });
        });
    </script>

@endsection

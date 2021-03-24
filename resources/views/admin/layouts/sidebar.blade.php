<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar navbar-collapse collapse">

        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>

            <li class="nav-item start active open">
                <a href="/admin/home" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">@lang('messages.main')</span>
                    <span class="selected"></span>

                </a>
            </li>

            <li class="heading">
                <h3 class="uppercase">@lang('messages.Side_menu')</h3>
            </li>

            <li class="nav-item {{ strpos(URL::current(), 'admins') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title">@lang( 'messages.manager')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ url('/admin/admins') }}" class="nav-link ">
                            <span class="title">@lang('messages.show_manager')</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/admins/create') }}" class="nav-link ">
                            <span class="title">@lang('messages.add_manager')</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/users') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title">@lang('messages.users')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ url('/admin/users') }}" class="nav-link ">
                            <span class="title">@lang('messages.employees')  </span>
                        </a>
                    </li>
                    {{--                    <li class="nav-item">--}}
                    {{--                        <a href="{{ url('/admin/add/user') }}" class="nav-link ">--}}
                    {{--                            <span class="title">أضافة موظف جديد</span>--}}
                    {{--                        </a>--}}
                    {{--                    </li>--}}

                    <li class="nav-item">
                        <a href="{{ url('/admin/clients') }}" class="nav-link ">
                            <span class="title">@lang('messages.clients')  </span>
                        </a>
                    </li>

                </ul>
            </li>

            {{--            <li class="nav-item {{ strpos(URL::current(), 'admin/edit/admin_email') !== false ? 'active' : '' }}">--}}
            {{--                <a href="{{ route('editAdminEmail') }}" class="nav-link ">--}}
            {{--                    <i class="icon-layers"></i>--}}
            {{--                    <span class="title"> أيميل  التطبيق </span>--}}
            {{--                    <span class="pull-right-container">--}}
            {{--                    </span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            {{--            <li class="nav-item {{ strpos(URL::current(), 'admin/intros') !== false ? 'active' : '' }}">--}}
            {{--                <a href="{{ route('Intro') }}" class="nav-link ">--}}
            {{--                    <i class="icon-layers"></i>--}}
            {{--                    <span class="title">  أنترو  التطبيق  </span>--}}
            {{--                    <span class="pull-right-container">--}}
            {{--                    </span>--}}
            {{--                </a>--}}
            {{--            </li>--}}


            <li class="nav-item {{ strpos(URL::current(), 'admin/roles') !== false ? 'active' : '' }}">
                <a href="{{ route('roles.index') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">  @lang('messages.Privacy')  </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/branches') !== false ? 'active' : '' }}">
                <a href="{{ route('branches.index') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">  @lang('messages.branches')  </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/categories') !== false ? 'active' : '' }}">
                <a href="{{ route('categories.index') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">  @lang('messages.Services')  </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/all-client-records') !== false ? 'active' : '' }}">
                <a href="{{ route('all_client_records') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">  @lang('messages.all_client_records')  </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/all-employee-records') !== false ? 'active' : '' }}">
                <a href="{{ route('all_employee_records') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">  @lang('messages.all_employee_records')  </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/rejected_dates') !== false ? 'active' : '' }}">
                <a href="{{ route('rejected_dates.index') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">  @lang('messages.rejected_dates')  </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/general-notifications') !== false ? 'active' : '' }}">
                <a href="{{ route('notifications.generalPage') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">  @lang('messages.General_Notifications')  </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>

            <li class="nav-item {{ strpos(URL::current(), 'admin/category-notifications') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title">@lang('messages.Custom_notifications') </span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{route('notifications.categoryPage')}}" class="nav-link ">
                            <span class="title">@lang('messages.notification_by_category')  </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('notifications.userPage')}}" class="nav-link ">
                            <span class="title">@lang('messages.notification_for_customers')  </span>
                        </a>
                    </li>


                </ul>
            </li>

            <li class="nav-item {{ strpos(URL::current(), 'admin/orderShifts') !== false ? 'active' : '' }}">
                <a href="{{ route('orderShifts.index') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">   @lang('messages.Periods')  </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>

            <li class="nav-item {{ strpos(URL::current(), 'admin/attendance') !== false ? 'active' : '' }}">
                <a href="{{ route('attendance.index') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">  @lang('messages.Attendance_and_Departure')  </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/sentences') !== false ? 'active' : '' }}">
                <a href="{{ route('sentences.index') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">@lang('messages.sentences_of_evaluation')  </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/new-orders') !== false ? 'active' : '' }}">
                <a href="{{route('new_orders') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">@lang('messages.new_orders')  </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            
                     <li class="nav-item {{ strpos(URL::current(), 'admin/calculation') !== false ? 'active' : '' }}">
                <a href="{{ route('calculation.index') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">  @lang('messages.simple_system')  </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>

{{--            <li class="nav-item {{ strpos(URL::current(), 'admin/new-orders') !== false ? 'active' : '' }}">--}}
{{--                <a href="javascript:;" class="nav-link nav-toggle">--}}
{{--                    <i class="icon-users"></i>--}}
{{--                    <span class="title">@lang('messages.orders')</span>--}}
{{--                    <span class="arrow"></span>--}}
{{--                </a>--}}
{{--                <ul class="sub-menu">--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{route('new_orders') }}" class="nav-link ">--}}
{{--                            <span class="title">@lang('messages.new_orders')  </span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('active_orders') }}" class="nav-link ">--}}
{{--                            <span class="title">@lang('messages.active_orders')</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('completed_orders') }}" class="nav-link ">--}}
{{--                            <span class="title">@lang('messages.completed_orders')</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('completed_ordersNotPaid') }}" class="nav-link ">--}}
{{--                            <span class="title">@lang('messages.completed_ordersNotPaid')</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('canceled_orders') }}" class="nav-link ">--}}
{{--                            <span class="title">@lang('messages.canceled_orders')</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}

{{--                </ul>--}}
{{--            </li>--}}


            <li class="nav-item {{ strpos(URL::current(), 'admin/complaints') !== false ? 'active' : '' }}">
                <a href="{{ route('all_complaint') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">@lang('messages.all_complaint') </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/sliders') !== false ? 'active' : '' }}">
                <a href="{{ route('sliders.index') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">  @lang('messages.sliders')  </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            {{-- <li class="nav-item {{ strpos(URL::current(), 'admin/contacts') !== false ? 'active' : '' }}">
                <a href="{{ route('Contact') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">@lang('messages.contact_us') </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li> --}}
            <li class="nav-item {{ strpos(URL::current(), 'admin/setting') !== false ? 'active' : '' }}">
                <a href="{{ route('setting.showPage') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">@lang('messages.setting') </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/change-logo') !== false ? 'active' : '' }}">
                <a href="{{ route('change_logo') }}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">@lang('messages.change_logo') </span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>


            {{--            <li class="nav-item {{ strpos(URL::current(), 'admin/payment_value') !== false ? 'active' : '' }}">--}}
            {{--                <a href="{{ route('payment_value') }}" class="nav-link ">--}}
            {{--                    <i class="icon-layers"></i>--}}
            {{--                    <span class="title"> قيمة الدفع </span>--}}
            {{--                    <span class="pull-right-container">--}}
            {{--                    </span>--}}
            {{--                </a>--}}
            {{--            </li>--}}
            {{--            <li class="nav-item {{ strpos(URL::current(), 'admin/parteners') !== false ? 'active' : '' }}">--}}
            {{--                <a href="{{ route('parteners') }}" class="nav-link ">--}}
            {{--                    <i class="icon-layers"></i>--}}
            {{--                    <span class="title">  الأشتراكات  المؤكدة </span>--}}
            {{--                    <span class="pull-right-container">--}}
            {{--                    </span>--}}
            {{--                </a>--}}
            {{--            </li>--}}



            {{--            <li class="nav-item {{ strpos(URL::current(), 'admin/pages') !== false ? 'active' : '' }}">--}}
            {{--                <a href="javascript:;" class="nav-link nav-toggle">--}}
            {{--                    <i class="icon-settings"></i>--}}
            {{--                    <span class="title">الصفحات</span>--}}
            {{--                    <span class="arrow"></span>--}}
            {{--                </a>--}}
            {{--                <ul class="sub-menu">--}}
            {{--                    <li class="nav-item  ">--}}
            {{--                        <a href="/admin/pages/about" class="nav-link ">--}}
            {{--                            <span class="title">من نحن</span>--}}
            {{--                        </a>--}}
            {{--                    </li>--}}
            {{--                    <li class="nav-item  ">--}}
            {{--                        <a href="/admin/pages/terms" class="nav-link ">--}}
            {{--                            <span class="title">الشروط والاحكام</span>--}}
            {{--                        </a>--}}
            {{--                    </li>--}}


            {{--                </ul>--}}
            {{--            </li>--}}


        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>

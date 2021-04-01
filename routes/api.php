<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function () {
    Route::group(['middleware' => 'cors', 'namespace' => 'Api'], function () {

        //general
        Route::get('branches', 'GeneralController@allBranch');
        Route::get('all-categories', 'GeneralController@allCategories');
        Route::post('branch-categories', 'GeneralController@branchCategories');
        Route::get('settings', 'GeneralController@settings');
        Route::get('rate-sentences', 'GeneralController@rateSentences');
        Route::get('qrcode','GeneralController@qrcode');

        /*client register*/

        Route::post('/client-register-mobile', [
            'uses' => 'Client\AuthController@registerMobile',
            'as' => 'register-mobile'
        ]);
        Route::post('/client-phone-verification', [
            'uses' => 'Client\AuthController@register_phone_post',
            'as' => 'register_phone_post'
        ]);
        Route::post('/client-register', [
            'uses' => 'Client\AuthController@register',
            'as' => 'register'
        ]);
        Route::post('/client-login', [
            'uses' => 'Client\AuthController@login',
            'as' => 'client-login'
        ]);
        Route::post('/resend-code', [
            'uses' => 'Client\AuthController@resend_code',
            'as' => 'resend_code'
        ]);

        Route::post('/client-forget-password', [
            'uses' => 'Client\AuthController@forgetPassword',
            'as' => 'client-forgetPassword'
        ]);
        Route::post('/client-confirm-reset-code', [
            'uses' => 'Client\AuthController@confirmResetCode',
            'as' => 'client-confirmResetCode'
        ]);
        Route::post('/client-reset-password', [
            'uses' => 'Client\AuthController@resetPassword',
            'as' => 'client-resetPassword'
        ]);

        /* Employee register*/


        Route::post('/employee-register-mobile', [
            'uses' => 'Employee\AuthController@registerMobile',
            'as' => 'employee-register-mobile'
        ]);
        Route::post('/employee-phone-verification', [
            'uses' => 'Employee\AuthController@register_phone_post',
            'as' => 'employee-register_phone_post'
        ]);
        Route::post('/employee-register', [
            'uses' => 'Employee\AuthController@register',
            'as' => 'employee-register'
        ]);
        Route::post('/employee-login', [
            'uses' => 'Employee\AuthController@login',
            'as' => 'employee-login'
        ]);
        Route::post('/employee-resend-code', [
            'uses' => 'Employee\AuthController@resend_code',
            'as' => 'employee-resend_code'
        ]);

        Route::post('/employee-forget-password', [
            'uses' => 'Employee\AuthController@forgetPassword',
            'as' => 'employee-forgetPassword'
        ]);
        Route::post('/employee-confirm-reset-code', [
            'uses' => 'Employee\AuthController@confirmResetCode',
            'as' => 'employee-confirmResetCode'
        ]);
        Route::post('/employee-reset-password', [
            'uses' => 'Employee\AuthController@resetPassword',
            'as' => 'employee-resetPassword'
        ]);

//======================================== rejected dates =======================
        Route::get('rejected-dates','GeneralController@rejectedDates');

        Route::group(['middleware' => 'auth:api'], function () {


            // ============== contact-us for employee && client ======
            Route::post('contact-us', 'GeneralController@contactUs');


            // ===================== bill page ============
            Route::post('bill-page', 'Employee\OrderController@billPage');


// clients
            Route::post('/client-change_password', [
                'uses' => 'Client\AuthController@changePassword',
                'as' => 'changePassword'
            ]);

            Route::post('/client-change-phone-number', [
                'uses' => 'Client\AuthController@change_phone_number',
                'as' => 'client_change_phone_number'
            ]);
            Route::post('/client_check_code_change_phone', [
                'uses' => 'Client\AuthController@check_code_changeNumber',
                'as' => 'client_check_code_change_email'
            ]);
            // client-profile-data
            Route::get('/client-get-client-data', 'Client\AuthController@getClientData');
            Route::post('/client-edit-profile', 'Client\AuthController@editProfile');

            // client locations
            Route::get('client-all-location', 'Client\LocationController@allLocation');
            Route::post('client-add-location', 'Client\LocationController@addLocation');
            Route::post('client-edit-location/{id}', 'Client\LocationController@editLocation');
            Route::post('client-delete-location', 'Client\LocationController@deleteLocation');
            // category
            Route::post('all-category', 'Client\GeneralController@allCategory');
            Route::post('all-category-depend-on-previous-order', 'Client\GeneralController@allCategoryDependOnPreviousOrder');
            Route::post('another-services-choose-list-of-them', 'Client\GeneralController@anotherServicesChooseListOfThem');

            // all available periods
            Route::post('available-periods', 'Client\GeneralController@availablePeriods');


            //orders for client
            Route::post('client-collect-order', 'Client\OrderController@collectOrder');
            Route::post('cancel-order','Client\OrderController@cancelOrder');
            Route::post('client-collect-another-services-with-order', 'Client\OrderController@anotherOrderSendCategories');
            Route::get('all-my-orders', 'Client\OrderController@allMyOrders');
            Route::post('is-paid', 'Client\OrderController@isPaid');
            Route::post('order-evaluation', 'Client\OrderController@orderEvaluation');
            Route::post('active-order-complaint', 'Client\OrderController@activeOrderComplaint');
            Route::post('rate-or-not', 'Client\OrderController@RatedOrNot');
            Route::post('slider', 'GeneralController@slider');

            Route::post('show-order','Client\OrderController@showOrder');
            //employees
            Route::post('/employee-change_password', [
                'uses' => 'Employee\AuthController@changePassword',
                'as' => 'employee_changePassword'
            ]);
            Route::post('/employee-change-phone-number', [
                'uses' => 'Employee\AuthController@change_phone_number',
                'as' => 'employee_change_phone_number'
            ]);

            Route::post('/employee_check_code_change_phone', [
                'uses' => 'Employee\AuthController@check_code_changeNumber',
                'as' => 'employee_check_code_change_email'
            ]);


            // employee-profile-data
            Route::get('/employee-get-data', 'Employee\AuthController@getEmployeeData');
            Route::post('/employee-edit-profile', 'Employee\AuthController@editProfile');
Route::post('change-language','Employee\AuthController@cahngeLanguage');

            // employee note
            Route::get('employee-all-note', 'Employee\NoteController@allNote');
            Route::post('employee-add-note', 'Employee\NoteController@addNote');
            Route::post('employee-edit-note/{id}', 'Employee\NoteController@editNote');
            Route::post('employee-delete-note', 'Employee\NoteController@deleteNote');


            //Attendance
            // ======== check-in
            Route::post('check-in', 'Employee\AttendanceController@checkIn');
            // ======== check-out
            Route::post('check-out', 'Employee\AttendanceController@checkOut');
// ========== check-the-show-button-to-add-check-in
            Route::post('check-the-show-button-to-add-check-in', 'Employee\AttendanceController@checkTheShowButtonToAddCheckIn');
            // page of attendance
            Route::post('attendance', 'Employee\AttendanceController@showCheckIn');

            // ================================ check-in && check-out page ===============================
            Route::get('show-all-check-in-and-check-out','Employee\AttendanceController@checkInCheckOut');
            Route::post('check-show-button','Employee\AttendanceController@checkShowButton');


            //=================== employee orders ======================
            Route::get('all-orders', 'Employee\OrderController@showMyOrder');
            Route::get('all-Appointments', 'Employee\OrderController@myAppointments');
            Route::post('reject-order', 'Employee\OrderController@rejectOrder');
            Route::post('start-order', 'Employee\OrderController@startOrder');
            Route::post('finish-order', 'Employee\OrderController@finishOrder');
            Route::get('all-active-orders', 'Employee\OrderController@activeOrder');
            Route::get('all-completed-orders', 'Employee\OrderController@completedOrder');
            Route::get('all-canceled-orders', 'Employee\OrderController@canceledOrder');
            Route::post('category-filter', 'Employee\OrderController@filterCategory');
            Route::post('date-filter', 'Employee\OrderController@filterDate');
            Route::post('cancel-order-by-employee','Employee\OrderController@cancelOrder');
            //======================== complete order in another day ==========================
            Route::post('reschedule_the_order','Employee\OrderController@rescheduleTheOrder');
            Route::get('busy-period-for-employee','Employee\OrderController@busyPeriodForEmployee');
            Route::post('reschedule','Employee\OrderController@reschedule');
            Route::get('waited-orders', 'Employee\OrderController@waitedOrders');
            Route::post('complete-start-order','Employee\OrderController@CompleteStartOrder');
            Route::post('complete-end-order','Employee\OrderController@CompleteEndOrder');
            Route::post('reschedule-again','Employee\OrderController@rescheduleAgain');


            //=============Service bill==============
            Route::post('fill-the-bill', 'Employee\OrderController@fillTheBill');

//======================= employee notification ==============
            Route::get('employee-notifications', 'Employee\NotificationController@listNotification');
            Route::post('employee-delete-notification', 'Employee\NotificationController@deleteNotification');
        });
        /*user register*/
        Route::post('/user-register-mobile', [
            'uses' => 'Api\AuthUserController@registerMobile',
            'as' => 'user-register-mobile'
        ]);
        Route::post('/user-phone-verification', [
            'uses' => 'Api\AuthUserController@register_phone_post',
            'as' => 'user-register_phone_post'
        ]);
        Route::post('/user-resend-code', [
            'uses' => 'Api\AuthUserController@resend_code',
            'as' => 'user-resend_code'
        ]);
        Route::post('/user-register', [
            'uses' => 'Api\AuthUserController@register',
            'as' => 'register'
        ]);
        Route::post('/user-login', [
            'uses' => 'Api\AuthUserController@login',
            'as' => 'user-login'
        ]);
        Route::post('/user-forget-password', [
            'uses' => 'Api\AuthUserController@forgetPassword',
            'as' => 'forgetPassword'
        ]);
        Route::post('/user-confirm-reset-code', [
            'uses' => 'Api\AuthUserController@confirmResetCode',
            'as' => 'user-confirmResetCode'
        ]);
        Route::post('/user-reset-password', [
            'uses' => 'Api\AuthUserController@resetPassword',
            'as' => 'user-resetPassword'
        ]);

        Route::post('/register', [
            'uses' => 'Api\AuthController@register',
            'as' => 'register'
        ]);
        Route::post('/login', [
            'uses' => 'Api\AuthController@login',
            'as' => 'login'
        ]);
        Route::post('/forget_password', [
            'uses' => 'Api\AuthController@forgetPassword',
            'as' => 'forgetPassword'
        ]);
        Route::post('/confirm_reset_code', [
            'uses' => 'Api\AuthController@confirmResetCode',
            'as' => 'confirmResetCode'
        ]);
        Route::post('/reset_password', [
            'uses' => 'Api\AuthController@resetPassword',
            'as' => 'resetPassword'
        ]);

//        Route::get('/terms_and_conditions', [
//            'uses' => 'Api\ProfileController@terms_and_conditions',
//            'as' => 'terms_and_conditions'
//        ]);

        Route::get('/about_us', [
            'uses' => 'Api\ProfileController@about_us',
            'as' => 'about_us'
        ]);
        Route::get('/services-images', [
            'uses' => 'GeneralController@servicesImages',
            'as' => 'services_images'
        ]);
        Route::get('/app_intro', [
            'uses' => 'Api\ProfileController@app_intro',
            'as' => 'app_intro'
        ]);
        Route::get('/get_payment_value', [
            'uses' => 'Api\UserController@get_payment_value',
            'as' => 'get_payment_value'
        ]);
        Route::get('/money', [
            'uses' => 'Api\UserController@money',
            'as' => 'money'
        ]);
        Route::post('/upload_excel_file', [
            'uses' => 'Api\UserController@upload_excel_file',
            'as' => 'upload_excel_file'
        ]);
        Route::get('/download_excel_file', [
            'uses' => 'Api\UserController@download_excel_file',
            'as' => 'download_excel_file'
        ]);

    });

    Route::get('/all-school', [
        'uses' => 'Api\DetailsController@school',
        'as' => 'school'
    ]);

    Route::group(['middleware' => ['auth:api', 'cors']], function () {

        /*notification*/
        Route::get('/list-notifications', 'Api\ApiController@listNotifications');
        Route::post('/delete_Notifications/{id}', 'Api\ApiController@delete_Notifications');

        Route::post('/contact_us', [
            'uses' => 'Api\ProfileController@contact_us',
            'as' => 'contact_us'
        ]);
        Route::post('/user_change_data', [
            'uses' => 'Api\AuthController@change_email',
            'as' => 'user_change_data'
        ]);
        Route::post('/user_check_code_change_email', [
            'uses' => 'Api\AuthController@check_code_changeNumber',
            'as' => 'user_check_code_change_email'
        ]);
        /*notification*/
        Route::post('/change_password', [
            'uses' => 'Api\AuthController@changePassword',
            'as' => 'changePassword'
        ]);
        Route::get('/get_my_subscription', [
            'uses' => 'Api\ProfileController@get_my_subscription',
            'as' => 'get_my_subscription'
        ]);

        //=============================   Use payment  route   ==============
        Route::post('/user_converted_to_paid', [
            'uses' => 'Api\UserController@postPayment',
            'as' => 'user_converted_to_paid'
        ]);


        //============================  backup routes ====================================
        Route::post('/create_backup', [
            'uses' => 'Api\BackupController@create_backup',
            'as' => 'create_backup'
        ]);
        Route::get('/get_backups', [
            'uses' => 'Api\BackupController@get_backups',
            'as' => 'get_backups'
        ]);
        Route::post('/store_backup_data/{id}', [
            'uses' => 'Api\BackupController@store_backup_data',
            'as' => 'store_backup_data'
        ]);
        Route::post('/create_class/{backup_id}', [
            'uses' => 'Api\BackupController@create_class',
            'as' => 'create_class'
        ]);
        Route::post('/create_student/{class_id}', [
            'uses' => 'Api\BackupController@create_student',
            'as' => 'create_student'
        ]);
        Route::post('/create_subject/{class_id}', 'Api\BackupController@create_subject');
        Route::post('/create_student_degrees/{student_id}/{subject_id}', 'Api\BackupController@create_student_degrees');
        Route::post('/create_student_attendees/{student_id}/{subject_id}', 'Api\BackupController@create_student_attendees');
        Route::post('/create_student_following/{student_id}/{subject_id}', 'Api\BackupController@create_student_following');
        Route::post('/delete_student_following/{student_id}/{subject_id}', 'Api\BackupController@delete_student_following');
        Route::get('/get_backup_data/{id}', 'Api\BackupController@get_backup_data');
        //============================  backup routes ====================================
//    ===========refreshToken ====================

        Route::post('/refresh-device-token', [
            'uses' => 'Api\DetailsController@refreshDeviceToken',
            'as' => 'refreshDeviceToken'
        ]);
        Route::post('/refreshToken', [
            'uses' => 'Api\DetailsController@refreshToken',
            'as' => 'refreshToken'
        ]);
        //===============   logout   ========================

        Route::post('/logout', [
            'uses' => 'Api\AuthController@logout',
            'as' => 'logout'
        ]);


    });


    //======================user app ====================
    Route::group(['middleware' => ['auth:api', 'cors']], function () {

        /*notification*/
        Route::get('/user-list-notifications', 'Api\ApiController@listNotifications');
        Route::post('/user-delete_Notifications/{id}', 'Api\ApiController@delete_Notifications');


        /*notification*/

        // order ============
        Route::post('/order', 'Api\OrderController@order_post');
        Route::get('/user-accept-offer/{id}', 'Api\OrderController@accept_sawaq_offers_price');
        Route::get('/user-refuse-offer/{id}', 'Api\OrderController@delete_sawaq_offers_price');
        Route::get('/offers', 'Api\ProfileController@sawaq_offers_price');
        Route::get('/get-order', 'Api\ProfileController@my_order');
        Route::get('/get-driver/{id}', 'Api\SawaqController@get_driver');
        Route::get('/get-user/{id}', 'Api\SawaqController@get_user');

        Route::get('/order-details/{id}', 'Api\ProfileController@order_details');
        Route::get('/order-offer/{id}', 'Api\ProfileController@order_offers');
        Route::post('/rate-driver/{id}', 'Api\ProfileController@rate_driver_user');


        //====================user app ====================
        Route::post('/user-change-password', [
            'uses' => 'Api\AuthUserController@changePassword',
            'as' => 'user_changePassword'
        ]);
        Route::post('/user-change-phone-number', [
            'uses' => 'Api\AuthUserController@change_phone_number',
            'as' => 'user_change_phone_number'
        ]);
        Route::post('/user-check-code-change-phone-number', [
            'uses' => 'Api\AuthUserController@check_code_changeNumber',
            'as' => 'user_check_code_changeNumber'
        ]);
        Route::post('/user-change-image', [
            'uses' => 'Api\AuthUserController@change_image',
            'as' => 'user_change_image'
        ]);
        //===============logout========================

        Route::post('/user-logout', [
            'uses' => 'Api\AuthUserController@logout',
            'as' => 'user-logout'
        ]);


    });

});

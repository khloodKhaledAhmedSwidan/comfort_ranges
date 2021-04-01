<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\User;

Route::get('locale/{locale}', function ($locale) {
    App::setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
});
Route::get('/', function () {
//    \Illuminate\Support\Facades\Artisan::call('check::commission');
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
/*admin panel routes*/

Route::get('/check-status/{id?}/{id1?}', 'Api\UserController@fatooraStatus');

Route::get('/fatoora/success', function () {
    return view('fatoora');
})->name('fatoora-success');

Route::get('/admin/home', ['middleware' => 'auth:admin', 'uses' => 'AdminController\HomeController@index'])->name('admin.home');

Route::get('remove_about_photo/{id}', 'AdminController\PageController@remove_about_photo')->name('imageAboutRemove');
Route::get('remove_intro_photo/{id}', 'AdminController\PageController@remove_intro_photo')->name('imageIntroRemove');


Route::prefix('admin')->group(function () {

    Route::get('login', 'AdminController\Admin\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'AdminController\Admin\LoginController@login')->name('admin.login.submit');
    Route::get('password/reset', 'AdminController\Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('password/email', 'AdminController\Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset/{token}', 'AdminController\Admin\ResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('password/reset', 'AdminController\Admin\ResetPasswordController@reset')->name('admin.password.update');
    Route::post('logout', 'AdminController\Admin\LoginController@logout')->name('admin.logout');

    Route::get('get/regions/{id}', 'AdminController\HomeController@get_regions');


    Route::group(['middleware' => ['web', 'auth:admin', 'auto-check-permission']], function () {


        // branches
        Route::resource('branches', 'AdminController\BranchController');
        Route::get('branches/delete/{id}', 'AdminController\BranchController@destroy')->name('deleteBranch');

        //============= sentences ==================
        Route::resource('sentences', 'AdminController\SentenceController');
        Route::get('sentences/delete/{id}', 'AdminController\SentenceController@destroy')->name('deleteSentence');


        //=========================== role ===============
        Route::resource('roles', 'AdminController\RoleController');
        route::get('roles/{id}/delete', 'AdminController\RoleController@destroy')->name('delete_role');


        //========================= sliders ========================================================
        Route::resource('sliders', 'AdminController\SliderController');
        route::get('sliders/{id}/delete', 'AdminController\SliderController@destroy')->name('delete_slider');

        //================== complaints ===================
        Route::get('complaints', 'AdminController\GeneralController@allComplaint')->name('all_complaint');

        // categories
        Route::resource('categories', 'AdminController\CategoryController');
        Route::get('categories/delete/{id}', 'AdminController\CategoryController@destroy')->name('deleteCategory');
        Route::get('update/activeCategory/{id}', 'AdminController\CategoryController@is_active')->name('active_category');
   //======================= calculation ==========================
        route::get('calculation','AdminController\CalculationController@index')->name('calculation.index');
        Route::get('/calculation/{from}/{to}','AdminController\CalculationController@fetch_data');
        Route::get('/orders/price/{fromPrice}/{toPrcie}','AdminController\CalculationController@fetch_Price');
        Route::get('/orders/material_cost/{fromPrice}/{toPrcie}','AdminController\CalculationController@materialCost');

        Route::get('/orders/count/{fromPrice}/{toPrcie}','AdminController\CalculationController@orderCount');


        //========================== rejected Dates ==========================================================
        Route::resource('rejected_dates', 'AdminController\RejectedDateController');
        Route::get('rejected_dates/delete/{id}', 'AdminController\RejectedDateController@destroy')->name('deleteCategory');


        //========================== rejected users ==========================================================
        Route::resource('rejected_users', 'AdminController\RejectedUserController');
        Route::get('rejected_users/delete/{id}', 'AdminController\RejectedUserController@destroy')->name('deleteRejectUsers');

        // order shifts
        Route::resource('orderShifts', 'AdminController\OrderShiftController');
        Route::get('orderShifts/delete/{id}', 'AdminController\OrderShiftController@destroy')->name('deleteOrderShift');

        //========================= orders ============================================
        Route::get('new-orders', 'AdminController\OrderController@newOrders')->name('new_orders');
        Route::get('category-Filter/{id}', 'AdminController\OrderController@categoryFilter')->name('categoryFilter');
        Route::get('active-orders', 'AdminController\OrderController@activeOrders')->name('active_orders');
        Route::get('completed-orders', 'AdminController\OrderController@completedOrders')->name('completed_orders');
        Route::get('waited-orders','AdminController\OrderController@waitedOrders')->name('waited_orders');
        Route::get('completed_orders-not-paid', 'AdminController\OrderController@completedOrdersNotPaid')->name('completed_ordersNotPaid');
        Route::get('canceled-orders', 'AdminController\OrderController@canceledOrders')->name('canceled_orders');
        Route::get('canceled-orders-before-started', 'AdminController\OrderController@canceledOrdersBeforeStated')->name('canceled_orders_before_started');
        Route::post('create-order', 'AdminController\OrderController@createOrder')->name('create_order');
        Route::get('create-order-page', 'AdminController\OrderController@createOrderPage')->name('create_orderPage');
        Route::get('edit-order-page/{id}', 'AdminController\OrderController@editOrderPage')->name('edit_orderPage');
        Route::get('show-order-page/{id}', 'AdminController\OrderController@showOrderPage')->name('show_orderPage');
        Route::get('show-bill-page/{id}', 'AdminController\OrderController@showBillPage')->name('showBill');
        Route::get('show-bill-completedOrder/{id}', 'AdminController\OrderController@showBillCompletedOrder')->name('showBillCompletedOrder');
        Route::post('update-order-page/{id}', 'AdminController\OrderController@updateOrderPage')->name('update_orderPage');
        Route::post('update-bill/{id}', 'AdminController\OrderController@editBill')->name('editBill');
        route::get('orders/remove/image/{order_id}', 'AdminController\OrderController@imageRemove')->name('remove_image');
        Route::post('change-order-status/{id}', 'AdminController\OrderController@changeOrderStatus')->name('changeOrderStatus');


        Route::get('edit-waited-order-page/{id}', 'AdminController\OrderController@editWaitedOrderPage')->name('edit_waitedOrderPage');
        Route::post('update-waitedOrder/{id}', 'AdminController\OrderController@updateWaitedOrderPage')->name('update_waitedOrderPage');

//    Route::get('get_employee/{id}','AdminController\OrderController@get_employee');
//========================== redirect order to another employee
        Route::get('redirect-order-to-another-employee/{id}', 'AdminController\OrderController@redirectOrderToEmployeePage')->name('redirect_order_to_another_employeePage');
        Route::post('redirect-order-to-another-employee/{id}', 'AdminController\OrderController@redirectOrderToEmployee')->name('redirect_order_to_another_employee');

//======================== show photo & vedio of order =============
        Route::get('show-images-of-order/{id}', 'AdminController\OrderController@showImagesOfOrder')->name('showImagesOfOrder');
        Route::get('show-vedio-of-order/{id}', 'AdminController\OrderController@showVedioOfOrder')->name('showVedioOfOrder');


        Route::get('setting', 'AdminController\SettingController@index')->name('setting.showPage');
        Route::post('add/settings', 'AdminController\SettingController@store')->name('setting_storeFun');
        Route::post('add/TermsAndConditions', 'AdminController\SettingController@storeTermsAndConditions')->name('store_TermsAndConditions');
        Route::post('add/AboutUs', 'AdminController\SettingController@storeAboutUs')->name('store_aboutus');
        Route::post('add/company-information', 'AdminController\SettingController@companyInformation')->name('store_companyInfo');
        Route::get('update/activeVatForCompleteOrder/{id}', 'AdminController\SettingController@is_active')->name('active_vat_order');

        Route::get('pages/about', 'AdminController\PageController@about');
        Route::post('add/pages/about', 'AdminController\PageController@store_about');


        Route::get('pages/terms', 'AdminController\PageController@terms');
        Route::post('add/pages/terms', 'AdminController\PageController@store_terms');


        //================================  Contacts   ======================================================= //
        Route::get('contacts', 'AdminController\ContactsController@index')->name('Contact');
        Route::get('contacts/create', 'AdminController\ContactsController@create')->name('createContact');
        Route::post('contacts/store', 'AdminController\ContactsController@store')->name('storeContact');
        Route::get('contacts/show/{id}', 'AdminController\ContactsController@show')->name('showContact');
        Route::get('contacts/{id}/edit', 'AdminController\ContactsController@edit')->name('editContact');
        Route::post('contacts/update/{id}', 'AdminController\ContactsController@update')->name('updateContact');
        Route::get('contacts/delete/{id}', 'AdminController\ContactsController@destroy')->name('deleteContact');
        Route::post('contacts/reply', 'AdminController\ContactsController@reply')->name('replyContact');
        //================================  Contacts   =======================================================


        // =============== change logo ==================================
        Route::get('/change-logo', 'AdminController\SettingController@changeLogo')->name('change_logo');
        Route::post('change-logo', 'AdminController\SettingController@LogoImage')->name('changeLogo.store');


        //============================= Start Payment Value ======================================
        Route::get('payment_value/create', 'AdminController\PageController@create')->name('payment_value');
        Route::post('payment_value/store', 'AdminController\PageController@store')->name('storePayment_value');
        //============================= End Payment Value ======================================


        Route::get('parteners', 'AdminController\SettingController@parteners')->name('parteners');

//        ===================================users============================================

        Route::get('users', 'AdminController\UserController@index')->name('show_all_employees');
        Route::get('add/user', 'AdminController\UserController@create')->name('add_new_employeePage');
        Route::post('add/user', 'AdminController\UserController@store')->name('add_new_employee');
        Route::get('get_categories/{id}', 'AdminController\UserController@get_categories');
        Route::get('edit/user/{id}', 'AdminController\UserController@edit')->name('edit_employee');
        Route::get('edit/userAccount/{id}', 'AdminController\UserController@edit_account');
        Route::post('update/userAccount/{id}', 'AdminController\UserController@update_account');
        Route::post('update/user/{id}', 'AdminController\UserController@update')->name('update_employee');
        Route::post('update/pass/{id}', 'AdminController\UserController@update_pass')->name('update_passEmployee');
        Route::post('update/privacy/{id}', 'AdminController\UserController@update_privacy')->name('update_privacyEmployee');
        Route::get('delete/{id}/user', 'AdminController\UserController@destroy')->name('delete_employee');
        Route::get('all-employee-records', 'AdminController\RecordController@allEmployeeOrders')->name('all_employee_records');
        Route::get('employee-records/{id}', 'AdminController\RecordController@showEmployeeOrders')->name('show_employee_records');
// ===============================clients ==============================================
        Route::get('clients', 'AdminController\ClientController@index')->name('show_all_clients');
        Route::get('add/client', 'AdminController\ClientController@create')->name('add_clientPage');
        Route::post('add/client', 'AdminController\ClientController@store')->name('add_client');
        Route::get('edit/client/{id}', 'AdminController\ClientController@edit')->name('edit_clientPage');
        Route::post('update/client/{id}', 'AdminController\ClientController@update')->name('update_client');
        Route::post('update/location/{id}', 'AdminController\ClientController@update_location')->name('location_client');
        Route::get('update/privacyClient/{id}', 'AdminController\ClientController@is_active')->name('privacy_client');
        Route::get('delete/{id}/client', 'AdminController\ClientController@destroy')->name('delete_client');
        Route::get('all-client-records', 'AdminController\RecordController@allClientOrders')->name('all_client_records');
        Route::get('client-records/{id}', 'AdminController\RecordController@showClientOrders')->name('show_client_records');


        // show check-in and check-out in admin section
        Route::get('attendance', 'AdminController\AttendanceController@index')->name('attendance.index');
        Route::get('attendance/user/{id}', 'AdminController\AttendanceController@showUserAttendance')->name('attendance.show');

        // ============= send notifications =========================
        Route::get('general-notifications', 'AdminController\NotificationController@generalNotificationPage')->name('notifications.generalPage');
        Route::post('general-notificationsSend', 'AdminController\NotificationController@generalNotification')->name('notifications.general');
        Route::get('category-notifications', 'AdminController\NotificationController@categoryNotificationPage')->name('notifications.categoryPage');
        Route::post('category-notificationsSend', 'AdminController\NotificationController@categoryNotification')->name('notifications.category');
        Route::get('user-notifications', 'AdminController\NotificationController@userNotificationPage')->name('notifications.userPage');
        Route::post('user-notificationsSend', 'AdminController\NotificationController@userNotification')->name('notifications.user');

//        ===========================school===========================================

        Route::get('school', 'AdminController\SchoolController@index');
        Route::get('add/school', 'AdminController\SchoolController@create');
        Route::post('add/school', 'AdminController\SchoolController@store');
        Route::get('edit/school/{id}', 'AdminController\SchoolController@edit');
        Route::post('update/school/{id}', 'AdminController\SchoolController@update');
        Route::get('delete/{id}/school', 'AdminController\SchoolController@destroy');

        Route::get('intros', 'AdminController\IntroController@index')->name('Intro');
        Route::get('add/intros', 'AdminController\IntroController@create')->name('createIntro');
        Route::post('add/intros', 'AdminController\IntroController@store')->name('storeIntro');
        Route::get('edit/intros/{id}', 'AdminController\IntroController@edit')->name('editIntro');
        Route::post('update/intros/{id}', 'AdminController\IntroController@update')->name('updateIntro');
        Route::get('delete/{id}/intros', 'AdminController\IntroController@destroy')->name('deleteIntro');


        // ======================================== Admin Email ============================================
        Route::get('edit/admin_email', 'AdminController\SettingController@edit')->name('editAdminEmail');
        Route::post('update/admin_email/{id}', 'AdminController\SettingController@update')->name('updateAdminEmail');

        //===============================================================


        // Admins Route
        Route::resource('admins', 'AdminController\AdminController');

        Route::get('/profile', [
            'uses' => 'AdminController\AdminController@my_profile',
            'as' => 'my_profile' // name
        ]);
        Route::post('/profileEdit', [
            'uses' => 'AdminController\AdminController@my_profile_edit',
            'as' => 'my_profile_edit' // name
        ]);
        Route::get('/profileChangePass', [
            'uses' => 'AdminController\AdminController@change_pass',
            'as' => 'change_pass' // name
        ]);
        Route::post('/profileChangePass', [
            'uses' => 'AdminController\AdminController@change_pass_update',
            'as' => 'change_pass' // name
        ]);

        Route::get('/admin_delete/{id}', [
            'uses' => 'AdminController\AdminController@admin_delete',
            'as' => 'admin_delete' // name
        ]);

    });


});


<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::prefix('admin-users')->name('admin-users/')->group(static function() {
            Route::get('/',                                             'AdminUsersController@index')->name('index');
            Route::get('/create',                                       'AdminUsersController@create')->name('create');
            Route::post('/',                                            'AdminUsersController@store')->name('store');
            Route::get('/{adminUser}/impersonal-login',                 'AdminUsersController@impersonalLogin')->name('impersonal-login');
            Route::get('/{adminUser}/edit',                             'AdminUsersController@edit')->name('edit');
            Route::post('/{adminUser}',                                 'AdminUsersController@update')->name('update');
            Route::delete('/{adminUser}',                               'AdminUsersController@destroy')->name('destroy');
            Route::get('/{adminUser}/resend-activation',                'AdminUsersController@resendActivationEmail')->name('resendActivationEmail');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::get('/profile',                                      'ProfileController@editProfile')->name('edit-profile');
        Route::post('/profile',                                     'ProfileController@updateProfile')->name('update-profile');
        Route::get('/password',                                     'ProfileController@editPassword')->name('edit-password');
        Route::post('/password',                                    'ProfileController@updatePassword')->name('update-password');
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::prefix('payment-channels')->name('payment-channels/')->group(static function() {
            Route::get('/',                                             'PaymentChannelsController@index')->name('index');
            Route::get('/create',                                       'PaymentChannelsController@create')->name('create');
            Route::post('/',                                            'PaymentChannelsController@store')->name('store');
            Route::get('/{paymentChannel}/edit',                        'PaymentChannelsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'PaymentChannelsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{paymentChannel}',                            'PaymentChannelsController@update')->name('update');
            Route::delete('/{paymentChannel}',                          'PaymentChannelsController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::prefix('address-types')->name('address-types/')->group(static function() {
            Route::get('/',                                             'AddressTypesController@index')->name('index');
            Route::get('/create',                                       'AddressTypesController@create')->name('create');
            Route::post('/',                                            'AddressTypesController@store')->name('store');
            Route::get('/{addressType}/edit',                           'AddressTypesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'AddressTypesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{addressType}',                               'AddressTypesController@update')->name('update');
            Route::delete('/{addressType}',                             'AddressTypesController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::prefix('roles')->name('roles/')->group(static function() {
            Route::get('/',                                             'RolesController@index')->name('index');
            Route::get('/create',                                       'RolesController@create')->name('create');
            Route::post('/',                                            'RolesController@store')->name('store');
            Route::get('/{role}/edit',                                  'RolesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'RolesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{role}',                                      'RolesController@update')->name('update');
            Route::delete('/{role}',                                    'RolesController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::prefix('creditors')->name('creditors/')->group(static function() {
            Route::get('/',                                             'CreditorsController@index')->name('index');
            Route::get('/create',                                       'CreditorsController@create')->name('create');
            Route::post('/',                                            'CreditorsController@store')->name('store');
            Route::get('/{creditor}/edit',                              'CreditorsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'CreditorsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{creditor}',                                  'CreditorsController@update')->name('update');
            Route::delete('/{creditor}',                                'CreditorsController@destroy')->name('destroy');
        });
    });
});
<?php
Route::redirect('/', 'admin/home');

Auth::routes(['register' => false]);

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

Route::group(['middleware' => ['auth', 'checkauth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    // Route::resource('permissions', 'Admin\PermissionsController');
    // Route::delete('permissions_mass_destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.mass_destroy');
    // Route::resource('roles', 'Admin\RolesController');
    // Route::delete('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy');
    // Route::resource('users', 'Admin\AdminController');

    Route::get('/', 'Admin\AdminController@index')->name('index');
    Route::get('create', 'Admin\AdminController@create')->name('create');
    Route::post('/', 'Admin\AdminController@store')->name('store');
    Route::get('{user}', 'Admin\AdminController@show')->name('show');
    Route::get('{user}/edit', 'Admin\AdminController@edit')->name('edit');
    Route::post('{user}', 'Admin\AdminController@update')->name('update');
    Route::post('destroy/{user}', 'Admin\AdminController@destroy')->name('destroy');

    Route::delete('users_mass_destroy', 'Admin\UsersController@massDestroy')->name('users.mass_destroy');
    Route::get('try-manual', 'Admin\PlayerController@tryManualGet')->name('player.try-manual.create');
    Route::post('try-manual', 'Admin\PlayerController@tryManualPost')->name('player.try-manual.store');
});

Route::group(['middleware' => ['auth', 'checkauth'], 'prefix' => 'superadmin', 'as' => 'superadmin.'], function () {
    Route::get('/', 'Superadmin\SuperadminController@index')->name('index');
    Route::get('create', 'Superadmin\SuperadminController@create')->name('create');
    Route::post('/', 'Superadmin\SuperadminController@store')->name('store');
    Route::get('{user}', 'Superadmin\SuperadminController@show')->name('show');
    Route::get('{user}/edit', 'Superadmin\SuperadminController@edit')->name('edit');
    Route::post('{user}', 'Superadmin\SuperadminController@update')->name('update');
    Route::post('destroy/{user}', 'Superadmin\SuperadminController@destroy')->name('destroy');
});

Route::group(['middleware' => ['auth', 'checkauth'], 'prefix' => 'player', 'as' => 'player.'], function () {
    Route::get('/', 'Player\PlayerController@index')->name('index');
    Route::get('create', 'Player\PlayerController@create')->name('create');
    Route::post('/', 'Player\PlayerController@store')->name('store');
    Route::get('{user}', 'Player\PlayerController@show')->name('show');
    Route::get('{user}/edit', 'Player\PlayerController@edit')->name('edit');
    Route::post('{user}', 'Player\PlayerController@update')->name('update');
    Route::post('destroy/{user}', 'Player\PlayerController@destroy')->name('destroy');
});

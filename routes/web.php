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

Route::get('/','PagesController@root')->name('root');

// 话题创建编辑中的图片上传路由
Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');

// Auth::routes();      // 为了直观显示,使用下面等价的路由信息

// 下面的路由等价于Auth::routes()生成的路由
// 用户身份验证相关的路由
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// 用户注册相关路由
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// 密码重置相关路由
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email 认证相关路由
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

// 首页也设置,不需要了
// Route::get('/home', 'HomeController@index')->name('home');

// 资源控制器不仅节省了大量的代码,还严格遵循了 RESTful URI 的规范 在实际项目中应该优先使用

// 用户控制器资源路由
Route::resource('users','UsersController',['only'=>['show','update','edit']]);
// 等价于 =>
// Route::get('/users/{user}','UsersController@show')->name('users.show');
// Route::get('/users/{user}/edit','UserController@edit')->name('Users.edit');
// Route::patch('/users/{user}','UsersController@update')->name('users.update');

// 话题控制器资源路由
Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
// show路由改为slug优化的路由
Route::get('topics/{topic}/{slug?}','TopicsController@show')->name('topics.show');
// URI 最后一个参数表达式 {slug?} ，? 意味着参数可选 即支持如下链接
// http://larabbs.test/topics/115
// http://larabbs.test/topics/115/slug-translation-test

// 话题分类资源路由
Route::resource('categories','CategoriesController',[
    'only' => ['show']
]);
<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index'));

// Logn Page
// 
Route::resource('login', 'LoginController', array('only' => array('index', 'store')));

// Logout Page
// 
Route::resource('logout', 'LogoutController', array('only' => array('index')));

// Purchase Orders
// 
Route::resource('pos', 'PoController');
Route::get('pos/{id}/pdf', array('as' => 'pos.pdf', 'uses' => 'PoController@pdf'));
Route::resource('pos.attachments', 'AttachmentController');
Route::resource('pos.manager-approval', 'ManagerApprovalController', array('only' => array('index', 'store')));
Route::resource('pos.accountant-approval', 'AccountantApprovalController', array('only' => array('index', 'store')));

// Team Po Controller (for the managers).
// 
Route::resource('team-pos', 'TeamPoController');

// Approval Controller (for accounting).
// 
Route::resource('approvals', 'ApprovalController', array('only' => array('index')));
Route::get('approvals/archives', array('as' => 'approvals.archives', 'uses' => 'ApprovalController@archives'));
Route::get('approvals/my', array('as' => 'approvals.my', 'uses' => 'ApprovalController@my'));

// Tokens
// 
Route::resource('token', 'TokenController', array('only' => array('index')));
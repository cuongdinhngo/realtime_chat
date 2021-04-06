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

Auth::routes();

Route::get("/", function(){
    return redirect()->route('messages.index');
});

Route::group(["middleware" => ['auth'], "prefix" => "messages", "namespace" => "Message", "as" => "messages."], function () {
    Route::get("/public", "MessageController@index")->name('index');
    Route::post("/public", "MessageController@store")->name('store');
});

Route::group(["prefix" => "users", "namespace" => "User", "as" => "users."], function () {
    Route::get("/current-user-login", "UserController@getCurrentUserLogin")->name('current-user-login');
    Route::get("/{id}", "UserController@getUser")->name('get-user');
    Route::get("/connect/{id}", "UserController@connect")->name('connect');
    Route::get("/notifications/list", "UserController@listNotifications")->name("list-notifications");
});

Route::group(["middleware" => ['auth'], "prefix" => "rooms", "namespace" => "Room", "as" => "rooms."], function () {
	Route::get("/list-user-rooms", "RoomController@listUserRooms")->name('list-user-rooms');
	Route::get("/", "RoomController@enterRoom")->name('enter');
	Route::post("/chat", "RoomController@chat")->name('chat');
	Route::get("/get-user-by-room", "RoomController@getUsersByRoomId")->name('get-user-by-room');
});

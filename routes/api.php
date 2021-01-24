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

// Unauthenticated api routes
Route::group(['prefix' => '', 'middleware' => 'throttle:50,5'], function () {
    Route::post('/login', 'api\Auth\LoginController@login');

    Route::get('/media/delete/{key}', 'api\MediaUploadController@deleteFile');

    Route::post('/comments/load', 'api\CommentsController@loadComments');

    Route::get('/subpabbles/search/{query}', 'api\SearchSubPabblesController@search');
    Route::get('/users/search/{query}', 'api\SearchUsersController@search');
});

// Authenticated api routes
Route::group(['prefix' => '', 'middleware' => ['throttle:50,5', 'auth:api']], function () {
    Route::post('/upload/media', 'api\MediaUploadController@upload');

    Route::post('/vote/{code}', 'api\VotesController@vote');

    Route::post('/subscribe/{name}', 'api\SubscriptionsApiController@subscribe');
    Route::post('/unsubscribe/{name}', 'api\SubscriptionsApiController@unsubscribe');

    Route::post('/comments/add', 'api\CommentsController@addComment');
    Route::post('/comments/load/auth', 'api\CommentsController@loadComments');

    Route::post('/thread/delete/{code}', 'api\ModerationController@deleteThread');
    Route::post('/comment/delete/{id}', 'api\ModerationController@deleteComment');
});

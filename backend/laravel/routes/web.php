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

/*
|  Post Routes
*/

Route::get('/', 'PostController@index')
    ->name('home');

Route::get('/post/{post}', 'PostController@permalink')
    ->name('permalink');

Route::middleware(['auth'])->group(function(){
    Route::get('/posts', 'PostController@adminIndex')
        ->name('posts');

    Route::get('/posts/create', 'PostController@create')
        ->name('createPost');

    Route::post('/posts', 'PostController@store')
        ->name('storePost');

    Route::get('/posts/{post}/edit', 'PostController@edit')
        ->name('editPost');

    Route::post('/posts/{post}', 'PostController@update')
        ->name('updatePost');

    Route::get('/posts/{post}/delete', 'PostController@delete')
        ->name('deletePost');
});

Route::get('/search/{terms?}', 'PhotoController@search')
    ->name('search');

/*
|  Comment Routes
*/

Route::post('/photos/{photo}/comment', 'CommentController@postComment')
    ->name('postComment');

Route::middleware(['auth'])->group(function(){
    Route::get('/comments', 'CommentController@adminIndex')
        ->name('comments');

    Route::get('/comments/{comment}/delete', 'CommentController@delete')
        ->name('deleteComment');
});


/*
|  Blacklist Routes
*/
Route::middleware(['auth'])->group(function(){
    Route::get('/blacklist', 'BlacklistController@index')
        ->name('blacklist');

    Route::post('/blacklist/store', 'BlacklistController@store')
        ->name('storeBlacklist');

    Route::get('/blacklist/{blacklist}/delete', 'BlacklistController@delete')
        ->name('deleteBlacklist');

    Route::post('/blacklist', 'BlacklistController@updateAll')
        ->name('updateAllBlacklist');
});


/*
|  Photo Routes
*/

Route::get('/photos/random', 'PhotoController@random')
    ->name('randomPhoto');

Route::get('/photos/{photo}', 'PhotoController@view')
    ->name('viewPhoto');

Route::get('/gallery', 'PhotoController@gallery')
    ->name('gallery');

Route::middleware(['auth'])->group(function(){
    Route::get('/photos', 'PhotoController@adminIndex')
        ->name('photos');

    Route::get('/photos/{photo}/addTag/{tag}', 'PhotoController@addTag')
        ->name('addTag');

    Route::get('/photos/{photo}/removeTag/{tag}', 'PhotoController@removeTag')
        ->name('removeTag');

    Route::get('/photos/{photo}/edit', 'PhotoController@edit')
        ->name('editPhoto');

    Route::post('/photos/{photo}', 'PhotoController@update')
        ->name('updatePhoto');

    Route::get('/photos/{photo}/delete', 'PhotoController@delete')
        ->name('deletePhoto');

    Route::post('/generateThumbnails', 'PhotoController@generateThumbnails')
        ->name('generateThumbnails');
});

Route::middleware(['auth'])->group(function(){
    Route::post('/upload', 'PhotoController@upload')
        ->name('upload');
});


/*
|  Tag Routes
*/
Route::get('/tag/{tags?}', 'PhotoController@filtered')
    ->name('filtered')
    ->where('tags', '!?[A-Za-z]+(,!?[A-Za-z]+)*');

Route::middleware(['auth'])->group(function(){
    Route::get('/tags', 'TagController@index')
        ->name('tags');

    Route::post('/tags/store', 'TagController@store')
        ->name('storeTag');

    Route::get('/tags/{tag}/delete', 'TagController@delete')
        ->name('deleteTag');

    Route::post('/tags', 'TagController@updateAll')
        ->name('updateAllTags');
});


/*
|  Auth Routes
*/

Route::get('/login', 'Auth\LoginController@showLoginForm')->middleware('guest')
    ->name('login');

Route::post('/login', 'Auth\LoginController@login')->middleware('guest');

Route::get('/logout', 'Auth\LoginController@logout')
    ->name('logout');

/*
|  Other Routes
*/

Route::view('/about', 'about')
    ->name('about');

Route::get('/stats', 'StatsController@index')
    ->name('stats');

Route::feeds();
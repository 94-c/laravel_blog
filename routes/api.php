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

Route::middleware('log.route')->group(function() {
    Route::post('/login', 'LoginController@login');
    Route::post('/register', 'LoginController@register');

    //이메일 인증
    Route::get('/email/verify/{id}', 'VerifyController@verifyEmail')->name('verification.verify');

    //비밀번호 찾기
    Route::post('/forgotPassword', 'UserController@forgotPassword');
    Route::post('/resetPassword', 'UserController@resetPassword');

    //소셜 네트워크 회원가입
    Route::get('/login/{provider}', 'ProviderController@redirectToProvider');
    Route::get('/login/{provider}/callback', 'ProviderController@handleProviderCallback');



    Route::middleware('auth:api')->group(function() {
        //login
        Route::get('/login-user', 'LoginController@getLoginUser');
        Route::get('/logout', 'LoginController@logout');

        //user
        Route::get('/users', 'UserController@findByUser');
        Route::put('/users/{user}', 'UserController@updateUser');
        Route::post('/users/disable', 'UserController@disableUser');
        Route::post('/users/enable', 'UserController@enableUser');
        Route::post('/users/destroy', 'UserController@destroy');
        Route::post('/users/profile-image', 'UserController@createOrUpdateProfileImage');
        Route::delete('/users/profile-image', 'UserController@deleteProfileImage');

        //post
        Route::get('/posts', 'PostController@findAllPosts');
        Route::get('/posts/{post}', 'PostController@findByPost');
        Route::post('/posts', 'PostController@createPost');
        Route::put('/posts/{post}', 'PostController@updatePost');
        Route::delete('/posts/{post}', 'PostController@deletePost');
        Route::get('/posts/{post}/likes', 'LikeController@findLikesByModel')->name('post.likes');
        Route::post('/posts/{post}/likes', 'LikeController@updateLike')->name('post.like');

        //file
        Route::get('/posts/{post}/files/{file}', 'FileController@downloadFile');
        Route::post('/posts/{post}/files', 'FileController@appendFile');
        Route::delete('/posts/{post}/files/{file}', 'FileController@deleteFile');

        //comment
        Route::get('/posts/{post}/comments', 'CommentController@findCommentsByPost');
        Route::post('/posts/{post}/comments', 'CommentController@createComment');
        Route::put('/posts/{post}/comments/{comment}', 'CommentController@updateComment');
        Route::delete('/posts/{post}/comments/{comment}', 'CommentController@deleteComment');
        Route::get('/posts/{post}/comments/{comment}/likes', 'LikeController@findLikesByModel');
        Route::post('/posts/{post}/comments/{comment}/likes', 'LikeController@updateLike');

        //Tag
        Route::get('/tags/posts', 'TagController@findPostsByTagName');
        Route::get('/tags/{tag}/posts', 'TagController@findPostsByTag');
        Route::delete('/posts/{post}/tags', 'TagController@deleteAllTagsFromPost');
        Route::delete('/posts/{post}/tags/{tag}', 'TagController@deleteTagFromPost');
    });
});

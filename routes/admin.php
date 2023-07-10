<?php

Route::middleware('log.route')->group(function() {
    Route::middleware('auth:api')->group(function() {
        Route::middleware('admin')->group(function() {
            /** user */
            Route::get('/users', 'UserController@findAllUsers');
            Route::get('/users/{user}', 'UserController@findByUser');
            Route::get('/users/{user}/posts', 'UserController@findPostsByUser');
            Route::get('/users/{user}/comments', 'UserController@findCommentsByUser');
            Route::put('/users/{user}/disable', 'UserController@disableUser');
            Route::put('/users/{user}/enable', 'UserController@enableUser');
            Route::put('/users/{user}/roles', 'UserController@updateUserRole');

            /** role */
            Route::get('/roles', 'RoleController@findAllRoles');
            Route::get('/roles/{role}/users', 'RoleController@findUsersByRole');

            /** post */
            Route::get('/posts', 'PostController@findAllPosts');
            Route::get('/posts/{post}', 'PostController@findByPost');
            Route::put('/posts/{post}/disable', 'PostController@disablePost');
            Route::put('/posts/{post}/enable', 'PostController@enablePost');

            /** comment */
            Route::get('/comments', 'CommentController@findAllComments');
            Route::get('/comments/{comment}', 'CommentController@findByComment');
            Route::put('/comments/{comment}/disable', 'CommentController@disableComment');
            Route::put('/comments/{comment}/enable', 'CommentController@enableComment');

            /** file */
            Route::get('/files', 'FileController@findAllFiles');
            Route::get('/images', 'FileController@findAllImages');

            /** tag */
            Route::get('/tags', 'TagController@findAllTags');
            Route::get('/tags/{tag}/posts', 'TagController@findPostsByTag');
            Route::get('/tags/posts', 'TagController@findPostsByTagName');
        });
    });
});


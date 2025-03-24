<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return response()->json([
        'message' => 'Hi'
    ]);
});

Route::prefix('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/posts', [PostController::class, 'index']);
        Route::post('/posts', [PostController::class, 'store']);
        Route::get('/posts/{post}', [PostController::class, 'show']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
        Route::get('/user/posts', [PostController::class, 'userPosts']);
        Route::get('/user/liked-posts', [PostController::class, 'likedPosts']);

        Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
        Route::post('/comments', [CommentController::class, 'store']);
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

        Route::post('/posts/{post}/like', [LikeController::class, 'togglePostLike']);
        Route::post('/comments/{comment}/like', [LikeController::class, 'toggleCommentLike']);
    });
});

Route::get('/sitemap', function () {
    $routes = collect(RouteFacade::getRoutes())->map(function ($route) {
        return [
            'uri' => $route->uri(),
            'method' => $route->methods(),
//            'name' => $route->getName(),
//            'middleware' => $route->gatherMiddleware(),
        ];
    });

    return response()->json($routes);
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Not Found'
    ], 404);
});

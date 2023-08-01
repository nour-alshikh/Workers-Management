<?php

use App\Http\Controllers\AdminDashboard\AdminDashboardController;
use App\Http\Controllers\AdminDashboard\PostStatusController;
use App\Http\Controllers\Auth\{AdminController, ClientController, WorkerController};
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReviewsController;
use Illuminate\Support\Facades\Route;


Route::controller(AdminController::class)->prefix('admins')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::controller(ClientController::class)->prefix('clients')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::controller(WorkerController::class)->prefix('workers')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('verify/{token}', 'verifyEmail');
});

Route::controller(PostController::class)->prefix('workers/post')->group(function () {
    Route::get('all-posts', 'index')->middleware('auth:admins');
    Route::get('pending-posts', 'getPending')->middleware('auth:admins');
    Route::get('approved-posts', 'getApproved');
    Route::get('posts/{id}', 'getPost');
    Route::post('add', 'store')->middleware('auth:workers');
});

Route::controller(PostStatusController::class)->prefix('admins/posts')->group(function () {
    Route::post('change-status', 'changeStatus')->middleware('auth:admins');
});

Route::controller(AdminDashboardController::class)->prefix('admins/notifications')->group(function () {
    Route::get('all', 'index')->middleware('auth:admins');
    Route::get('unread', 'unread')->middleware('auth:admins');
    Route::get('mark-all-as-read', 'markAllAsRead')->middleware('auth:admins');
    Route::post('mark-as-read/{id}', 'markAsRead')->middleware('auth:admins');
    Route::delete('delete-all', 'deleteAll')->middleware('auth:admins');
    Route::delete('delete-notification/{id}', 'deleteNotification')->middleware('auth:admins');
});

Route::controller(OrderController::class)->prefix('clients/orders')->group(function () {
    Route::post('add-order', 'addOrder')->middleware('auth:clients');
});

Route::controller(ReviewsController::class)->prefix('clients/reviews')->group(function () {
    Route::post('add-review', 'addReview')->middleware('auth:clients');
});

Route::controller(ReviewsController::class)->prefix('workers/reviews')->group(function () {
    Route::get('get-post-reviews/{id}', 'getPostReviews')->middleware('auth:workers');
});

Route::controller(OrderController::class)->prefix('workers/orders')->group(function () {
    Route::post('change-status/{id}', 'changeStatus')->middleware('auth:workers');
});

Route::controller(OrderController::class)->prefix('workers/orders')->group(function () {
    Route::post('get-worker-orders', 'getClientOrders')->middleware('auth:workers');
});

Route::get('/unauthorized', function () {
    return response()->json([
        'message' => "Unauthorized"
    ], 401);
})->name('login');

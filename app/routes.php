<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use Slim\App;

return function (App $app) {
    session_start();

    // Home
    $app->get('/', [HomeController::class, 'index']);

    // Auth
    $app->get('/login', [AuthController::class, 'showLoginForm']);
    $app->post('/login', [AuthController::class, 'login']);
    $app->get('/register', [AuthController::class, 'showRegisterForm']);
    $app->post('/register', [AuthController::class, 'register']);
    $app->get('/logout', [AuthController::class, 'logout']);

    // Dashboard
    $app->get('/dashboard', [DashboardController::class, 'index']);
    $app->get('/dashboard/board/create', [DashboardController::class, 'createBoardForm']);
    $app->post('/dashboard/board/create', [DashboardController::class, 'storeBoard']);
    $app->get('/dashboard/task/create', [DashboardController::class, 'createTaskForm']);
    $app->post('/dashboard/task/create', [DashboardController::class, 'storeTask']);
    $app->get('/dashboard/task/{id}', [DashboardController::class, 'viewTask']); // View More
};

<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Medoo\Medoo;
use Slim\Views\Twig;
use Psr\Container\ContainerInterface;
use App\Controllers\DashboardController;
use App\Controllers\AuthController;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        // Twig View dengan akses session global
        Twig::class => function () {
            // Mulai session jika belum aktif
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);

            // Tambahkan session ke dalam Twig sebagai global variable
            $twig->getEnvironment()->addGlobal('session', $_SESSION);

            return $twig;
        },

        // Database (Medoo)
        'db' => function () {
            return new Medoo([
                'type' => 'mysql',
                'host' => 'localhost',
                'database' => 'slimpro',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8mb4',
            ]);
        },

        // AuthController
        AuthController::class => function (ContainerInterface $c) {
            return new AuthController(
                $c->get(Twig::class),
                $c->get('db')
            );
        },

        // DashboardController
        DashboardController::class => function (ContainerInterface $c) {
            return new DashboardController(
                $c->get(Twig::class)
            );
        },
    ]);
};

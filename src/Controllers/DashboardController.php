<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class DashboardController
{
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function index(Request $request, Response $response): Response
    {
        $user = $_SESSION['user'] ?? null;

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        // Data dummy untuk ditampilkan di dashboard
        $tasks = [
            [
                'id' => 1,
                'title' => 'New Dashboard Design',
                'description' => 'Desain tampilan baru untuk dashboard admin.',
                'status' => 'NEW',
                'created_at' => '2025-05-28',
                'deadline' => '2025-07-17',
                'tags' => ['#SPK-01', 'UI/UX'],
                'likes' => 12,
                'comments' => 2,
                'members' => ['/img/user1.jpg', '/img/user2.jpg', '/img/user3.jpg']
            ],
            [
                'id' => 2,
                'title' => 'Perbaiki Validasi Form',
                'description' => 'Validasi field input untuk form login.',
                'status' => 'TODO',
                'created_at' => '2025-06-01',
                'deadline' => '2025-07-20',
                'tags' => ['#SPK-02', 'Backend'],
                'likes' => 5,
                'comments' => 1,
                'members' => ['/img/user4.jpg', '/img/user5.jpg']
            ],
            [
                'id' => 3,
                'title' => 'Integrasi API Pembayaran',
                'description' => 'Menghubungkan sistem dengan API Midtrans.',
                'status' => 'ON GOING',
                'created_at' => '2025-06-10',
                'deadline' => '2025-07-25',
                'tags' => ['#SPK-03', 'API'],
                'likes' => 8,
                'comments' => 3,
                'members' => ['/img/user6.jpg']
            ],
            [
                'id' => 4,
                'title' => 'Code Review Module User',
                'description' => 'Review semua perubahan kode di modul user.',
                'status' => 'IN REVIEW',
                'created_at' => '2025-07-01',
                'deadline' => '2025-07-21',
                'tags' => ['#SPK-04', 'Code Review'],
                'likes' => 3,
                'comments' => 4,
                'members' => ['/img/user7.jpg', '/img/user8.jpg']
            ],
            [
                'id' => 5,
                'title' => 'Laporan Proyek Bulanan',
                'description' => 'Finalisasi dan kirim laporan proyek ke manajer.',
                'status' => 'COMPLETED',
                'created_at' => '2025-06-15',
                'deadline' => '2025-07-10',
                'tags' => ['#SPK-05', 'Dokumentasi'],
                'likes' => 15,
                'comments' => 6,
                'members' => ['/img/user9.jpg']
            ]
        ];

        return $this->view->render($response, 'dashboard.twig', [
            'user' => $user,
            'tasks' => $tasks
        ]);
    }

    public function createBoardForm(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'partials/create_board.twig');
    }

    public function storeBoard(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $_SESSION['flash'] = 'Board berhasil dibuat.';
        return $response->withHeader('Location', '/dashboard')->withStatus(302);
    }

    public function createTaskForm(Request $request, Response $response): Response
    {
        $status = $request->getQueryParams()['status'] ?? 'NEW';
        return $this->view->render($response, 'partials/create_task.twig', ['status' => $status]);
    }

    public function storeTask(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $_SESSION['flash'] = 'Task berhasil ditambahkan.';
        return $response->withHeader('Location', '/dashboard')->withStatus(302);
    }

    public function viewTask(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];

        $dummyTasks = [
            1 => [
                'id' => 1,
                'title' => 'New Dashboard Design',
                'description' => 'Desain tampilan baru untuk dashboard admin.',
                'status' => 'NEW',
                'created_at' => '2025-05-28',
                'deadline' => '2025-07-17',
                'tags' => ['#SPK-01', 'UI/UX'],
                'likes' => 12,
                'comments' => 2,
                'members' => ['/img/user1.jpg', '/img/user2.jpg', '/img/user3.jpg']
            ],
            2 => [
                'id' => 2,
                'title' => 'Perbaiki Validasi Form',
                'description' => 'Validasi field input untuk form login.',
                'status' => 'TODO',
                'created_at' => '2025-06-01',
                'deadline' => '2025-07-20',
                'tags' => ['#SPK-02', 'Backend'],
                'likes' => 5,
                'comments' => 1,
                'members' => ['/img/user4.jpg', '/img/user5.jpg']
            ],
            3 => [
                'id' => 3,
                'title' => 'Integrasi API Pembayaran',
                'description' => 'Menghubungkan sistem dengan API Midtrans.',
                'status' => 'ON GOING',
                'created_at' => '2025-06-10',
                'deadline' => '2025-07-25',
                'tags' => ['#SPK-03', 'API'],
                'likes' => 8,
                'comments' => 3,
                'members' => ['/img/user6.jpg']
            ],
            4 => [
                'id' => 4,
                'title' => 'Code Review Module User',
                'description' => 'Review semua perubahan kode di modul user.',
                'status' => 'IN REVIEW',
                'created_at' => '2025-07-01',
                'deadline' => '2025-07-21',
                'tags' => ['#SPK-04', 'Code Review'],
                'likes' => 3,
                'comments' => 4,
                'members' => ['/img/user7.jpg', '/img/user8.jpg']
            ],
            5 => [
                'id' => 5,
                'title' => 'Laporan Proyek Bulanan',
                'description' => 'Finalisasi dan kirim laporan proyek ke manajer.',
                'status' => 'COMPLETED',
                'created_at' => '2025-06-15',
                'deadline' => '2025-07-10',
                'tags' => ['#SPK-05', 'Dokumentasi'],
                'likes' => 15,
                'comments' => 6,
                'members' => ['/img/user9.jpg']
            ]
        ];

        if (!isset($dummyTasks[$id])) {
            $response->getBody()->write('Task not found');
            return $response->withStatus(404);
        }

        return $this->view->render($response, 'task_detail.twig', [
            'task' => $dummyTasks[$id]
        ]);
    }
}

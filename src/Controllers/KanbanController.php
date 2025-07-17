<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Medoo\Medoo;

class KanbanController
{
    protected $view;
    protected $db;

    public function __construct(Twig $view, Medoo $db)
    {
        $this->view = $view;
        $this->db = $db;
    }

    // Tampilkan semua task yang dikelompokkan berdasarkan status
    public function index(Request $request, Response $response): Response
    {
        $tasks = $this->db->select("tbl_kanbans", "*");

        $grouped = [];
        foreach ($tasks as $task) {
            $grouped[$task['status']][] = $task;
        }

        return $this->view->render($response, 'dashboard.twig', [
            'grouped_tasks' => $grouped
        ]);
    }

    // Tampilkan form buat task baru
    public function showCreateForm(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'kanban_create.twig');
    }

    // Simpan task baru ke database
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $this->db->insert("tbl_kanbans", [
            "title" => $data["title"],
            "description" => $data["description"],
            "created_at" => $data["created_at"],
            "deadline" => $data["deadline"],
            "tag" => $data["tag"],
            "category" => $data["category"],
            "status" => $data["status"],
            "likes" => 0,
            "comments" => 0,
            "assignees" => $data["assignees"] ?? null
        ]);

        return $response->withHeader('Location', '/dashboard')->withStatus(302);
    }
}
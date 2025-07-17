<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ContactController
{
    protected $db;

    public function __construct($container)
    {
        $this->db = $container->get('db');
    }

    public function submit(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $nama = trim($data['nama'] ?? '');
        $email = trim($data['email'] ?? '');
        $pesan = trim($data['pesan'] ?? '');

        if ($nama && $email && $pesan) {
            $this->db->insert('tbl_contacts', [
                'nama' => $nama,
                'email' => $email,
                'pesan' => $pesan
            ]);

            // Flash message
            $_SESSION['success_message'] = 'Pesan Anda berhasil dikirim!';
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }
}

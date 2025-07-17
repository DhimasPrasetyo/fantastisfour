<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use Medoo\Medoo;

class TeamController
{
    protected $view, $db;

    public function __construct(Twig $view, Medoo $db)
    {
        $this->view = $view;
        $this->db = $db;
    }

    public function index($request, $response): ResponseInterface
    {
        $teams = $this->db->select("teams", "*");
        return $this->view->render($response, 'teams/list.twig', ['teams' => $teams]);
    }

    public function createForm($request, $response): ResponseInterface
    {
        return $this->view->render($response, 'teams/create.twig');
    }

    public function store($request, $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $this->db->insert("teams", [
            'name' => $data['name'],
            'description' => $data['description'],
            'created_at' => date('Y-m-d')
        ]);
        return $response->withHeader('Location', '/teams')->withStatus(302);
    }

    public function editForm($request, $response, $args): ResponseInterface
    {
        $team = $this->db->get("teams", "*", ["id" => $args['id']]);
        return $this->view->render($response, 'teams/edit.twig', ['team' => $team]);
    }

    public function update($request, $response, $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $this->db->update("teams", [
            'name' => $data['name'],
            'description' => $data['description']
        ], ["id" => $args['id']]);

        return $response->withHeader('Location', '/teams')->withStatus(302);
    }

    public function delete($request, $response, $args): ResponseInterface
    {
        $this->db->delete("teams", ["id" => $args['id']]);
        return $response->withHeader('Location', '/teams')->withStatus(302);
    }

    public function members($request, $response, $args): ResponseInterface
    {
        $team = $this->db->get("teams", "*", ["id" => $args['id']]);
        $members = $this->db->select("team_members", "*", ["team_id" => $args['id']]);
        return $this->view->render($response, 'teams/members.twig', [
            'team' => $team,
            'members' => $members
        ]);
    }
    public function addMember($request, $response, $args): ResponseInterface
{
    $data = $request->getParsedBody();
    $this->db->insert("team_members", [
        "team_id" => $args['id'],
        "name" => $data["name"],
        "role" => $data["role"],
        "joined_at" => $data["joined_at"]
    ]);

    return $response->withHeader('Location', "/teams/{$args['id']}/members")->withStatus(302);
}

public function deleteMember($request, $response, $args): ResponseInterface
{
    $this->db->delete("team_members", ["id" => $args['member_id']]);
    return $response->withHeader('Location', "/teams/{$args['id']}/members")->withStatus(302);
}

}
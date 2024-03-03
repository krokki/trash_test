<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;

class Comments extends BaseController
{
    private BaseConnection $connect;

    public function __construct()
    {
        //TODO: Я всё таки бээкенд рарзработчик, фронтенд сделан при помощи chatGPT.
        // Готов обсудить технические вопросы, не касаемые убогого codeigniter and javascript-a

        $this->connect = db_connect();
    }

    public function index(int $page)
    {
        $limit = 3;

        try {
            $offset = ($page - 1) * $limit;

            $comments = $this->connect->query("
                SELECT SQL_CALC_FOUND_ROWS *
                FROM comments
                LIMIT $limit
                OFFSET $offset
            ")->getResultArray();

            $totalCount = $this->connect->query('SELECT FOUND_ROWS() as total_count;')->getResultArray();

        } catch (\Throwable $e) {
            return $this->response->setBody($e->getMessage());
        }

        $pages = $totalCount[0]['total_count'] / $limit;

        return view('comments/index.php', ['comments' => $comments, 'pages' => $pages]);
    }

    public function getPaginatedList(int $page): ResponseInterface
    {
        $limit = 3;
        $offset = $page * $limit;

        $comments = $this->connect->query("SELECT * FROM comments OFFSET $offset LIMIT $limit")->getResultArray();

        $this->response->setStatusCode(Response::HTTP_CREATED);
        return $this->response->setBody();
    }

    public function addComment(): ResponseInterface
    {
        $params = $this->request->getPost();

        $email = $params['name'];
        $comment = $params['text'];
        $createdAt = (new \DateTime())->format('Y-m-d H:i:s');

        try {
            $this->connect->query("
                INSERT INTO comments (name, text, date)
                VALUES ('$email', '$comment', '$createdAt')
        ");

        } catch (\Throwable $e) {
            $this->response->setBody(json_encode($e->getMessage()));
        }

        $lastInset = $this->connect->insertID();
        $createdComment = $this->connect->query("SELECT * FROM comments WHERE id = $lastInset")->getResultArray();

        $this->response->setBody(json_encode($createdComment));

        $this->response->setStatusCode(Response::HTTP_CREATED);

        return $this->response;
    }

    public function deleteComment($commentId): ResponseInterface
    {
        $this->connect->query("
                DELETE FROM comments
                WHERE id = $commentId 
        ");

        $this->response->setBody($commentId);
        $this->response->setStatusCode(Response::HTTP_CREATED);

        return $this->response;
    }
}
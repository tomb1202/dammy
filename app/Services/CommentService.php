<?php

namespace App\Services;

use App\Models\Comment;
use App\Repositories\CommentRepository;

class CommentService extends BaseService
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        parent::__construct($commentRepository);
        $this->commentRepository = $commentRepository;
    }

    public function storeOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            return $this->updateData($data['id'], $data);
        } else {
            return $this->create($data);
        }
    }

    public function create(array $data)
    {
        return Comment::create($data);
    }

    public function updateData(int $id, array $data)
    {
        $comment = Comment::findOrFail($id);
        $comment->update($data);
        return $comment;
    }
}

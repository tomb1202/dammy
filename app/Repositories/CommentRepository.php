<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository extends EloquentRepository
{
    protected $model;

    public function __construct(Comment $model)
    {
        $this->model = $model;
    }
}

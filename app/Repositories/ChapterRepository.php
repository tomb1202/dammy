<?php

namespace App\Repositories;

use App\Models\Chapter;

class ChapterRepository extends EloquentRepository
{
    protected $model;

    public function __construct(Chapter $model)
    {
        $this->model = $model;
    }
}

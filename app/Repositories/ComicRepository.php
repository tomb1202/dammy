<?php

namespace App\Repositories;

use App\Models\Comic;

class ComicRepository extends EloquentRepository
{
    protected $model;

    public function __construct(Comic $model)
    {
        $this->model = $model;
    }
}

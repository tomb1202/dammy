<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends EloquentRepository
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}

<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;

class CategoryService extends BaseService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        parent::__construct($categoryRepository);
        $this->categoryRepository = $categoryRepository;
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
        return Category::create($data);
    }

    public function updateData(int $id, array $data)
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category;
    }
}

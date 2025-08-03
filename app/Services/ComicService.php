<?php

namespace App\Services;

use App\Models\Comic;
use App\Repositories\ComicRepository;
use Illuminate\Support\Facades\Storage;

class ComicService extends BaseService
{
    protected $comicRepository;

    public function __construct(ComicRepository $comicRepository)
    {
        parent::__construct($comicRepository);
        $this->comicRepository = $comicRepository;
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
        // Tách categories để xử lý sau
        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids']);

        if (request()->hasFile('image')) {
            $data['image'] = storeUploadedImage(request()->file('image'));
        }

        $comic = Comic::create($data);

        if (!empty($categoryIds)) {
            $comic->categories()->sync($categoryIds);
        }

        return $comic;
    }

    public function updateData(int $id, array $data)
    {
        $comic = Comic::findOrFail($id);

        // Tách categories
        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids']);

        if (request()->hasFile('image')) {
            if ($comic->image && Storage::disk('public')->exists($comic->image)) {
                Storage::disk('public')->delete($comic->image);
            }

            $data['image'] = storeUploadedImage(request()->file('image'));
        }

        $comic->update($data);

        if (!empty($categoryIds)) {
            $comic->categories()->sync($categoryIds);
        }

        return $comic;
    }
}

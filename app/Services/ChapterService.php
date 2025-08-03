<?php

namespace App\Services;

use App\Models\Chapter;
use App\Models\ChapterPage;
use App\Repositories\ChapterRepository;
use Illuminate\Support\Facades\DB;

class ChapterService extends BaseService
{
    protected $chapterRepository;

    public function __construct(ChapterRepository $chapterRepository)
    {
        parent::__construct($chapterRepository);

        $this->chapterRepository = $chapterRepository;
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
        return DB::transaction(function () use ($data) {
            $chapter = Chapter::create($data);


            if (!empty($data['new_pages'])) {
                $usedSorts = [];

                foreach ($data['new_pages'] as $item) {
                    if (!isset($item['image']) || !$item['image'] instanceof \Illuminate\Http\UploadedFile) {
                        continue;
                    }

                    $sort = isset($item['sort']) ? (int)$item['sort'] : 0;

                    // Tăng sort nếu bị trùng
                    while (in_array($sort, $usedSorts)) {
                        $sort++;
                    }

                    $usedSorts[] = $sort;

                    $file = $item['image'];
                    $fileName = 'chapter-' . $data['comic_id'] . '-page-' . $sort . '.webp';
                    $imagePath = storeUploadedImage($file, false, $fileName);

                    ChapterPage::create([
                        'chapter_id'   => $chapter->id,
                        'image'        => $imagePath,
                        'page_number'  => $sort,
                        'sort'         => $sort,
                    ]);
                }
            }

            return $chapter;
        });
    }

    public function updateData(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $chapter = Chapter::with('pages')->findOrFail($id);
            $chapter->update($data);

            // 1. Thu thập ID các page hiện tại từ input
            $inputPageIds = collect($data['pages'] ?? [])->pluck('id')->filter()->toArray();

            // 2. Xoá các pages không còn trong danh sách
            ChapterPage::where('chapter_id', $chapter->id)
                ->whereNotIn('id', $inputPageIds)
                ->delete();

            // 3. Cập nhật sort cho các pages còn lại
            $usedSorts = [];

            foreach ($data['pages'] as $pageData) {
                if (!empty($pageData['id'])) {
                    $sort = (int) $pageData['sort'];
                    $usedSorts[] = $sort;

                    ChapterPage::where('id', $pageData['id'])
                        ->where('chapter_id', $chapter->id)
                        ->update(['sort' => $sort, 'page_number' => $sort]);
                }
            }

            // 4. Thêm các page mới (check trùng sort trong cả usedSorts)
            if (!empty($data['new_pages'])) {
                foreach ($data['new_pages'] as $item) {
                    if (!isset($item['image']) || !$item['image'] instanceof \Illuminate\Http\UploadedFile) {
                        continue;
                    }

                    $sort = isset($item['sort']) ? (int) $item['sort'] : 0;

                    // Nếu trùng sort, tự tăng tới khi không trùng
                    while (in_array($sort, $usedSorts)) {
                        $sort++;
                    }

                    $usedSorts[] = $sort;

                    $file = $item['image'];
                    $fileName = 'chapter-' . $data['comic_id'] . '-page-' . $sort . '.webp';
                    $imagePath = storeUploadedImage($file, false, $fileName);

                    ChapterPage::create([
                        'chapter_id'   => $chapter->id,
                        'image'        => $imagePath,
                        'page_number'  => $sort,
                        'sort'         => $sort,
                    ]);
                }
            }

            return $chapter;
        });
    }
}

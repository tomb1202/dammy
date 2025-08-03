<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChapterRequest;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\ChapterPage;
use App\Services\ChapterService;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    protected $chapterService;

    public function __construct(ChapterService $chapterService)
    {
        $this->chapterService = $chapterService;
    }

    public function index(Request $request)
    {
        $filters = [];

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $filters[] = function ($query) use ($search) {
                $query->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
            };
        }

        if ($request->filled('comic_id')) {
            $filters['comic_id'] = ['operator' => '=', 'value' => $request->comic_id];
        }

        $data = $this->chapterService->paginateWithFilters($filters, 50, ['comic', 'pages'], ['pages']);

        return view('admin.chapter.index', [
            'data' => $data,
            'request' => $request
        ]);
    }

    public function edit(Chapter $chapter)
    {
        if ($chapter) {
            $chapter->load('comic', 'pages');
        }

        return view('admin.chapter.form', [
            'chapter' => $chapter
        ]);
    }

    public function store(StoreChapterRequest $request)
    {
        $data = $request->validated();
        $chapter = $this->chapterService->storeOrUpdate($data);

        return redirect()->route('admin.chapter.edit', ['chapter' => $chapter->id])
            ->with('success', 'Chapter saved successfully!');
    }

    public function delete(Request $request)
    {
        $chapter = Chapter::find($request->id);

        if (!$chapter) {
            return response()->json(['success' => false, 'message' => 'Chapter not found.'], 404);
        }

        $chapter->delete();

        return response()->json(['success' => true, 'message' => 'Chapter deleted successfully.']);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Chapter::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Đã xóa thành công']);
        }
        return response()->json(['success' => false, 'message' => 'Không có mục nào được chọn']);
    }
}

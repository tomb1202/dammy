<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreComicRequest;
use App\Models\Category;
use App\Models\Comic;
use App\Services\ComicService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ComicController extends Controller
{
    protected $comicService;

    public function __construct(ComicService $comicService)
    {
        $this->comicService = $comicService;
    }

    public function index(Request $request)
    {
        $filters = [];

        // Tìm kiếm theo title hoặc slug
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $filters[] = function ($query) use ($search) {
                $query->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(slug) LIKE ?', ["%{$search}%"]);
            };
        }

        // Lọc theo category
        if ($request->filled('category_id')) {
            $categoryId = $request->input('category_id');
            $filters[] = function ($query) use ($categoryId) {
                $query->whereHas('categories', function ($q) use ($categoryId) {
                    $q->where('categories.id', $categoryId);
                });
            };
        }

        // Lọc theo status
        if ($request->filled('status')) {
            $filters['status'] = ['operator' => '=', 'value' => $request->status];
        }

        // Bắt buộc phải có crawl = 1
        $filters['crawl'] = ['operator' => '=', 'value' => 1];

        // Bắt buộc phải có ít nhất 1 chapter
        $filters[] = function ($query) {
            $query->whereHas('chapters');
        };

        $data = $this->comicService->paginateWithFilters($filters, 30, ['categories'], ['chapters']);

        $categories = Category::orderBy('name')->get();

        return view('admin.comic.index', [
            'data' => $data,
            'request' => $request,
            'categories' => $categories,
        ]);
    }


    public function create()
    {
        // Lấy tất cả category để render select option
        $categories = Category::orderBy('name')->get();

        return view('admin.comic.form', [
            'categories' => $categories,
        ]);
    }

    public function edit(Comic $comic)
    {

        $comic = Comic::with(['categories', 'chapters' => function ($q) {
            $q->withCount('pages');
        }])->findOrFail($comic->id);

        // Lấy tất cả category để render select option
        $categories = Category::orderBy('name')->get();

        return view('admin.comic.form', [
            'comic' => $comic,
            'categories' => $categories,
        ]);
    }


    public function store(StoreComicRequest $request)
    {
        $data = $request->validated();
        $this->comicService->storeOrUpdate($data);

        return redirect()->route('admin.comic.index')->with('success', 'Comic saved successfully!');
    }

    public function delete(Request $request)
    {
        $comic = Comic::find($request->id);

        if (!$comic) {
            return response()->json(['success' => false, 'message' => 'Comic not found.'], 404);
        }

        $comic->delete();

        return response()->json(['success' => true, 'message' => 'Comic deleted successfully.']);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Comic::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Đã xóa thành công']);
        }
        return response()->json(['success' => false, 'message' => 'Không có mục nào được chọn']);
    }

    public function view($id)
    {
        $comic = Comic::with(['chapters' => function ($q) {
            $q->withCount('pages');
        }])->findOrFail($id);

        return response()->json([
            'success' => true,
            'comic' => $comic
        ]);
    }

    public function search(Request $request)
    {
        $q = $request->input('q');

        $results = Comic::select('id', 'title')
            ->when($q, function ($query, $q) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($q) . '%']);
            })
            ->orderBy('title')
            ->limit(20)
            ->get();

        return response()->json($results);
    }
}

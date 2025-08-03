<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $filters = [];

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);

            $filters[] = function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(slug) LIKE ?', ["%{$search}%"]);
            };
        }

        $data = $this->categoryService->paginateWithFilters($filters, 30, ['comics'], ['comics']);

        return view('admin.category.index', [
            'data' => $data,
            'request' => $request,
        ]);
    }


    public function edit(Category $category)
    {
        return view('admin.category.form', [
            'category' => $category,
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $this->categoryService->storeOrUpdate($data);

        return redirect()->route('admin.category.index')->with('success', 'Category saved successfully!');
    }

    public function delete(Request $request)
    {
        $category = Category::find($request->id);

        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found.'], 404);
        }

        $category->delete();

        return response()->json(['success' => true, 'message' => 'Category deleted successfully.']);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Category::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Đã xóa thành công']);
        }
        return response()->json(['success' => false, 'message' => 'Không có mục nào được chọn']);
    }

    public function view($id)
    {
        $category = Category::findOrFail($id);

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }
}

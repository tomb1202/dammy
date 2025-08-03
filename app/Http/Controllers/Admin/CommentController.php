<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $filters = [];

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);

            $filters[] = function ($query) use ($search) {
                $query->whereRaw('LOWER(content) LIKE ?', ["%{$search}%"]);
            };
        }

        $data = $this->commentService->paginateWithFilters($filters, 30, ['user', 'chapter']);
        
        return view('admin.comment.index', [
            'data' => $data,
            'request' => $request,
        ]);
    }

    public function edit(Comment $comment)
    {
        return view('admin.comment.form', [
            'comment' => $comment,
        ]);
    }

    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();
        $this->commentService->storeOrUpdate($data);

        return redirect()->route('admin.comment.index')->with('success', 'Comment saved successfully!');
    }

    public function delete(Request $request)
    {
        $comment = Comment::find($request->id);

        if (!$comment) {
            return response()->json(['success' => false, 'message' => 'Comment not found.'], 404);
        }

        $comment->delete();

        return response()->json(['success' => true, 'message' => 'Comment deleted successfully.']);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Comment::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Đã xóa thành công']);
        }
        return response()->json(['success' => false, 'message' => 'Không có mục nào được chọn']);
    }

    public function view($id)
    {
        $comment = Comment::with(['user', 'chapter'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'comment' => $comment
        ]);
    }
}

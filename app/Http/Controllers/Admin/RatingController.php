<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        $filters = [];

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);

            $filters[] = function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
                })->orWhereHas('comic', function ($q) use ($search) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
                });
            };
        }

        $data = Rating::with(['user', 'comic'])
            ->when(!empty($filters), function ($query) use ($filters) {
                foreach ($filters as $filter) {
                    $query->where($filter);
                }
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('admin.rating.index', [
            'data' => $data,
            'request' => $request,
        ]);
    }

    public function delete(Request $request)
    {
        $rating = Rating::find($request->id);

        if (!$rating) {
            return response()->json(['success' => false, 'message' => 'Rating not found.'], 404);
        }

        $rating->delete();

        return response()->json(['success' => true, 'message' => 'Rating deleted successfully.']);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Rating::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Đã xóa thành công']);
        }
        return response()->json(['success' => false, 'message' => 'Không có mục nào được chọn']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(
        UserService $userService,
    ) {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $filters = [];

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);

            $filters[] = function ($query) use ($search) {
                $query->whereRaw('LOWER(name) ILIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(email) ILIKE ?', ["%{$search}%"]);
            };
        }

        $data = $this->userService->paginateWithFilters($filters, 10);

        return view('admin.user.index', [
            'data' => $data,
            'request' => $request,
        ]);
    }


    public function edit(User $user)
    {
        return view('admin.user.form', [
            'user' => $user,
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $this->userService->storeOrUpdate($data);

        return redirect()->route('admin.user.index')->with('success', 'User saved successfully!');
    }

    public function delete(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            User::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Đã xóa thành công']);
        }
        return response()->json(['success' => false, 'message' => 'Không có mục nào được chọn']);
    }

    public function view($id)
    {
        $user = User::with(['country'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}

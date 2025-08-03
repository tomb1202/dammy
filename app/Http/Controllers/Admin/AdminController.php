<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreAdminRequest;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Services\AdminService;
use Carbon\Carbon;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index()
    {
        $today = Carbon::today();

        return view('admin.dashboard.index', [
            'totalUsers' => User::count(),
            'totalComics' => Comic::where('crawl', 1)
                ->whereHas('chapters')
                ->count(),
            'totalChapters' => Chapter::count(),
            'totalComments' => Comment::count(),

            'todayUsers' => User::whereDate('created_at', $today)->count(),
            'todayComics' => Comic::whereDate('created_at', $today)->count(),
            'todayChapters' => Chapter::whereDate('created_at', $today)->count(),

            'latestUsers' => User::whereDate('created_at', $today)->orderBy('created_at', 'desc')->take(8)->get(),
        ]);
    }

    public function accounts()
    {
        // Đọc dữ liệu từ Slave
        $data = $this->adminService->getAll();
        return view('admin.account.index', [
            'data' => $data
        ]);
    }

    public function detail($id)
    {
        // Đọc dữ liệu từ Slave
        $admin = $this->adminService->findById($id);
        return response()->json($admin);
    }

    public function store(StoreAdminRequest $request)
    {
        // Ghi dữ liệu vào Master
        $data = $request->validated();
        $this->adminService->storeOrUpdateAdmin($data);

        return redirect()->route('admin.account.index')->with('success', 'Admin created/updated successfully');
    }

    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function login()
    {
        return view('admin.auth.login');
    }

    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) return redirect()->back();
        if (Auth::guard('admin')->attempt(request(['email', 'password']), $request->remember)) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('admin.login')->with('message', ' Đăng nhập không thành công');
        }
    }

    public function upgrading()
    {
        return view('admin.layouts.upgrade');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\User;
use App\Models\ViewHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function info()
    {
        if (!auth()->check()) {
            abort(403, 'Bạn cần đăng nhập để sử dụng tính năng này');
        }

        $user = auth()->user();

        // Truyện đang theo dõi
        $follows = Comic::whereHas('follows', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with('latestChapter')
            ->get();

        // Lấy các comic_id theo lịch sử xem gần nhất của user
        $comicIds = ViewHistory::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->pluck('comic_id')
            ->unique()
            ->take(10)
            ->toArray();

        // Truy vấn truyện từ danh sách comic_id
        $histories = Comic::with(['latestChapter'])
            ->whereIn('id', $comicIds)
            ->get()
            ->sortBy(function ($comic) use ($comicIds) {
                return array_search($comic->id, $comicIds); // Giữ thứ tự theo lịch sử
            });

        return view('site.user.info', [
            'user' => $user,
            'follows' => $follows,
            'histories' => $histories,
        ]);
    }

    public function update(Request $request)
    {
        $user = User::find(auth()->user()->id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập tên hiển thị.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',
            'current_password.required_with' => 'Bạn phải nhập mật khẩu hiện tại để đổi mật khẩu.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Cập nhật name + email
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Đổi mật khẩu nếu có nhập
        if (!empty($validated['password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.'])
                    ->withInput();
            }

            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255|unique:users,name',
            'email'  => 'required|email|max:255|unique:users,email',
            'password'   => 'required|string|min:6',
        ], [
            'name.required' => 'Tên đăng nhập là bắt buộc.',
            'name.unique' => 'Tên đăng nhập đã được sử dụng.',
            'email.required' => 'Email là bắt buộc.',
            'email-sign-up.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được sử dụng.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công!',
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'redirect' => url('/')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email hoặc mật khẩu không đúng.'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true, 'message' => 'success']);
    }

    public function destroy($comicId)
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['message' => 'Chưa đăng nhập'], 403);
        }

        ViewHistory::where('user_id', $userId)
            ->where('comic_id', $comicId)
            ->delete();

        return response()->json(['message' => 'Đã xoá lịch sử']);
    }
}

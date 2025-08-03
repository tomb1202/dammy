<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdvController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\ComicController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController as ControllersUserController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/test-download-image', [Controller::class, 'downloadTest']);

Route::middleware(['admin'])
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        // account
        Route::get('/accounts', [AdminController::class, 'accounts'])->name('account.index');
        Route::post('/account/store', [AdminController::class, 'store'])->name('account.store');
        Route::get('/upgrading', [AdminController::class, 'upgrading'])->name('upgrading');

        // user
        Route::prefix('users')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
            Route::get('/view/{id}', [UserController::class, 'view'])->name('view');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::delete('/delete', [UserController::class, 'delete'])->name('delete');
            Route::delete('/delete-multiple', [UserController::class, 'deleteMultiple'])->name('delete-multiple');
            Route::get('/deleted', [UserController::class, 'indexDeletedUsers'])->name('deleted');
        });

        // comics
        Route::prefix('comics')->name('comic.')->group(function () {
            Route::get('/', [ComicController::class, 'index'])->name('index');
            Route::get('/create', [ComicController::class, 'create'])->name('create');
            Route::get('/edit/{comic}', [ComicController::class, 'edit'])->name('edit');
            Route::get('/view/{id}', [ComicController::class, 'view'])->name('view');
            Route::post('/store', [ComicController::class, 'store'])->name('store');
            Route::delete('/delete', [ComicController::class, 'delete'])->name('delete');
            Route::delete('/delete-multiple', [ComicController::class, 'deleteMultiple'])->name('delete-multiple');
            Route::get('/search', [ComicController::class, 'search'])->name('search');
        });

        // chapters
        // chapters
        Route::prefix('chapters')->name('chapter.')->group(function () {
            Route::get('/', [ChapterController::class, 'index'])->name('index');
            Route::get('/edit/{chapter?}', [ChapterController::class, 'edit'])->name('edit');
            Route::post('/store', [ChapterController::class, 'store'])->name('store');
            Route::delete('/delete', [ChapterController::class, 'delete'])->name('delete');
            Route::delete('/delete-multiple', [ChapterController::class, 'deleteMultiple'])->name('delete-multiple');
        });

        // categories
        Route::prefix('categories')->name('category.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/edit/{category}', [CategoryController::class, 'edit'])->name('edit');
            Route::post('/store', [CategoryController::class, 'store'])->name('store');
            Route::delete('/delete', [CategoryController::class, 'delete'])->name('delete');
            Route::delete('/delete-multiple', [CategoryController::class, 'deleteMultiple'])->name('delete-multiple');
        });

        // comments
        Route::prefix('comments')->name('comment.')->group(function () {
            Route::get('/', [CommentController::class, 'index'])->name('index');
            Route::get('/edit/{comment}', [CommentController::class, 'edit'])->name('edit');
            Route::get('/view/{id}', [CommentController::class, 'view'])->name('view');
            Route::post('/store', [CommentController::class, 'store'])->name('store');
            Route::delete('/delete', [CommentController::class, 'delete'])->name('delete');
            Route::delete('/delete-multiple', [CommentController::class, 'deleteMultiple'])->name('delete-multiple');
        });

        // ratings
        Route::prefix('ratings')->name('rating.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::delete('/delete', [UserController::class, 'delete'])->name('delete');
            Route::delete('/delete-multiple', [UserController::class, 'deleteMultiple'])->name('delete-multiple');
        });

        // follows
        Route::prefix('follows')->name('follow.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::delete('/delete', [UserController::class, 'delete'])->name('delete');
            Route::delete('/delete-multiple', [UserController::class, 'deleteMultiple'])->name('delete-multiple');
        });

        //adv
        Route::get('/advs', [AdvController::class, 'index'])->name('adv.index');

        Route::prefix('adv')->name('adv.')->group(function () {
            Route::post('/store', [AdvController::class, 'store'])->name('store');
            Route::get('/banner', [AdvController::class, 'banner'])->name('banner');
            Route::get('/banner-script', [AdvController::class, 'bannerScript'])->name('banner-script');
            Route::get('/catfish', [AdvController::class, 'catfish'])->name('catfish');
            Route::get('/preload', [AdvController::class, 'preload'])->name('preload');
            Route::get('/pushjs', [AdvController::class, 'pushjs'])->name('pushjs');
            Route::get('/popupjs', [AdvController::class, 'popupjs'])->name('popupjs');
            Route::get('/text-link', [AdvController::class, 'textLink'])->name('text-link');
            Route::get('/header', [AdvController::class, 'header'])->name('header');
            Route::get('/bottom', [AdvController::class, 'bottom'])->name('bottom');
            Route::post('/refresh', [AdvController::class, 'refresh'])->name('refresh');
            Route::post('/active', [AdvController::class, 'active'])->name('active');
            Route::post('/delete/{id}', [AdvController::class, 'delete'])->name('delete');
        });

        // setting
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/store', [SettingController::class, 'store'])->name('settings.store');

        Route::get('/artisan-runner', function () {
            return view('admin.setting.artisan-runner');
        });

        Route::post('/artisan-runner', function (\Illuminate\Http\Request $request) {
            $phpPath = '/usr/bin/php';
            $command = $phpPath . ' artisan ' . escapeshellcmd($request->input('command'));

            $process = Symfony\Component\Process\Process::fromShellCommandline($command, base_path());
            $process->run();

            return response()->json([
                'success' => $process->isSuccessful(),
                'output' => $process->getOutput(),
                'error' => $process->getErrorOutput(),
            ]);
        });
    });


Route::get('/view-image', function () {
    $image = request()->query('image');

    if (empty($image)) {
        abort(404, 'Image is required.');
    }

    // Cache theo hash md5 của URL trong 1 ngày
    $cacheKey = 'resolved_image_' . md5($image);
    $resolvedUrl = Cache::remember($cacheKey, now()->addDay(), fn() => chapter_image_url($image));

    if (empty($resolvedUrl)) {
        abort(404, 'Image could not be resolved.');
    }

    return redirect()->away($resolvedUrl);
})->name('view-image');

// admin
Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/login',  [AdminController::class, 'postLogin'])->name('admin.post.login');
Route::post('/logout',  [AdminController::class, 'logout'])->name('admin.logout');


// site
Route::get('/', [HomeController::class, 'index']);

Route::post('/user/register', [ControllersUserController::class, 'register'])->name('site.register');
Route::post('/user/login', [ControllersUserController::class, 'login'])->name('site.login');
Route::post('/user/reset-password', [ControllersUserController::class, 'sendResetLinkEmail'])->name('password.email');

Route::middleware(['check.user.login'])->group(function () {
    Route::post('/user/logout', [ControllersUserController::class, 'logout'])->name('user.logout');
    Route::get('/user/setting', [ControllersUserController::class, 'info'])->name('user.info');
    Route::post('/user/setting', [ControllersUserController::class, 'update'])->name('user.setting');
    Route::delete('/history/{comicId}', [ControllersUserController::class, 'destroy'])->name('history.destroy');
});

Route::get('/the-loai', [PageController::class, 'categories'])->name('categories');
Route::get('/truyen-full', [PageController::class, 'completed'])->name('completed');

Route::get('/lich-su-xem', [PageController::class, 'history'])->name('site.history');
Route::get('/tim-kiem', [PageController::class, 'search'])->name('search');

Route::get('/{categorySlug}', [PageController::class, 'category'])->name('site.category');

Route::get('/truyen/{slug}', [ViewController::class, 'show'])->name('comic.show');
Route::get('/truyen/{comicSlug}/chuong/{chapterSlug}', [ViewController::class, 'chapter'])->name('chapter.show');


Route::get('/the-loai/manhwa', [PageController::class, 'manhwa'])->name('manhwa');
Route::get('/the-loai/manga', [PageController::class, 'manhwa'])->name('manga');


Route::get('/storage/uploads/advs/{path?}', function ($path) {
    $cacheKey = 'adv_' . $path;

    if (Cache::store('file')->has($cacheKey)) {
        $imageString = Cache::store('file')->get($cacheKey);
    } else {
        $imagePath = storage_path('app/public/uploads/advs/' . $path);

        if (!file_exists($imagePath)) {
            $imagePath = public_path('system/img/no-image.png');
        }

        $imageString = file_get_contents($imagePath);

        Cache::store('file')->put($cacheKey, $imageString, now()->addMinutes(60));
    }

    $response = response($imageString)->header('Content-Type', 'image/gif');
    $response->header('Cache-Control', 'public, max-age=31536000');
    return $response;
})->name('web.adv.banner');
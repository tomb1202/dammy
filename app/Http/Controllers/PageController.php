<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comic;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function category(Request $request, $categorySlug)
    {
        // Lấy category theo slug
        $category = Category::where('slug', $categorySlug)->firstOrFail();

        // Kiểu sắp xếp
        $order = $request->query('m_orderby', 'latest');

        // Base query: comics thuộc category, đã crawl, có ít nhất 1 chapter
        $query = $category->comics()
            ->with('latestChapter')
            ->where('crawl', 1)
            ->has('chapters');

        // Sắp xếp
        switch ($order) {
            case 'rating':
                $query->orderByDesc('ratings');
                break;
            case 'views':
                $query->orderByDesc('views');
                break;
            case 'new':
                $query->orderByDesc('created_at');
                break;
            case 'latest':
            default:
                $query->orderByDesc('updated_at');
                break;
        }

        // Phân trang
        $comics = $query->paginate(20)->appends(['m_orderby' => $order]);

        // Truyện mới (không cần phân trang)
        $newComics = Comic::with('latestChapter')
            ->where('hidden', 0)
            ->where('crawl', 1)
            ->has('chapters')
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        return view('site.pages.category', [
            'category'   => $category,
            'comics'     => $comics,
            'm_orderby'  => $order,
            'newComics'  => $newComics
        ]);
    }

    public function categories()
    {
        $categories = Category::withCount('comics')
            ->orderByDesc('comics_count')
            ->get();

        return view('site.pages.categories', [
            'categories' => $categories,
        ]);
    }

    public function history()
    {
        // Random 10 comics đã crawl và có chương
        $comics = Comic::with('latestChapter')
            ->where('crawl', 1)
            ->has('chapters')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        $categories = Category::all();

        return view('site.pages.history', [
            'comics'     => $comics,
            'categories' => $categories,
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('s', '');
        $order = $request->query('m_orderby', 'latest');

        $query = Comic::with('latestChapter')
            ->where('title', 'LIKE', '%' . $keyword . '%')
            ->where('crawl', 1)
            ->has('chapters');

        switch ($order) {
            case 'rating':
                $query->orderByDesc('ratings');
                break;
            case 'views':
                $query->orderByDesc('views');
                break;
            case 'new':
                $query->orderByDesc('created_at');
                break;
            case 'latest':
            default:
                $query->orderByDesc('updated_at');
                break;
        }

        $comics = $query->paginate(20)->appends([
            's' => $keyword,
            'm_orderby' => $order
        ]);

        $newComics = Comic::with('latestChapter')
            ->where('hidden', 0)
            ->where('crawl', 1)
            ->has('chapters')
            ->orderBy('updated_at', 'desc')
            ->limit(12)
            ->get();

        return view('site.pages.search', [
            'comics'     => $comics,
            'keyword'    => $keyword,
            'm_orderby'  => $order,
            'newComics'  => $newComics
        ]);
    }

    public function completed(Request $request)
    {
        $order = $request->query('m_orderby', 'latest');

        $query = Comic::with('latestChapter')
            ->where('status', 'complete')
            ->where('crawl', 1)
            ->has('chapters');

        switch ($order) {
            case 'rating':
                $query->orderByDesc('ratings');
                break;
            case 'views':
                $query->orderByDesc('views');
                break;
            case 'new':
                $query->orderByDesc('created_at');
                break;
            case 'latest':
            default:
                $query->orderByDesc('updated_at');
                break;
        }

        $comics = $query->paginate(20)->appends([
            'm_orderby' => $order
        ]);

        $newComics = Comic::with('latestChapter')
            ->where('hidden', 0)
            ->where('crawl', 1)
            ->has('chapters')
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        return view('site.pages.completed', [
            'comics'    => $comics,
            'm_orderby' => $order,
            'newComics' => $newComics,
        ]);
    }

    public function manhwa(Request $request)
    {
        $order = $request->query('m_orderby', 'latest');

        $query = Comic::with('latestChapter')
            ->where('hidden', 0)
            ->where('crawl', 1)
            ->has('chapters');

        if ($order === 'random') {
            $query->inRandomOrder();
        } else {
            switch ($order) {
                case 'rating':
                    $query->orderByDesc('ratings');
                    break;
                case 'views':
                    $query->orderByDesc('views');
                    break;
                case 'new':
                    $query->orderByDesc('created_at');
                    break;
                case 'latest':
                default:
                    $query->orderByDesc('updated_at');
                    break;
            }
        }

        $comics = $query->paginate(20)->appends(['m_orderby' => $order]);

        $categories = Category::all();

        $newComics = Comic::with('latestChapter')
            ->where('hidden', 0)
            ->where('crawl', 1)
            ->has('chapters')
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        return view('site.pages.manhwa', [
            'comics'     => $comics,
            'categories' => $categories,
            'newComics'  => $newComics,
            'm_orderby'  => $order
        ]);
    }

    public function manga(Request $request)
    {
        $order = $request->query('m_orderby', 'latest');

        $query = Comic::with('latestChapter')
            ->where('hidden', 0)
            ->where('crawl', 1)
            ->has('chapters');

        if ($order === 'random') {
            $query->inRandomOrder();
        } else {
            switch ($order) {
                case 'rating':
                    $query->orderByDesc('ratings');
                    break;
                case 'views':
                    $query->orderByDesc('views');
                    break;
                case 'new':
                    $query->orderByDesc('created_at');
                    break;
                case 'latest':
                default:
                    $query->orderByDesc('updated_at');
                    break;
            }
        }

        $comics = $query->paginate(20)->appends(['m_orderby' => $order]);

        $categories = Category::all();

        $newComics = Comic::with('latestChapter')
            ->where('hidden', 0)
            ->where('crawl', 1)
            ->has('chapters')
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        return view('site.pages.manga', [
            'comics'     => $comics,
            'categories' => $categories,
            'newComics'  => $newComics,
            'm_orderby'  => $order
        ]);
    }
}

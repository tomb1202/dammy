<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Điều kiện chung
        $baseQuery = Comic::with('latestChapter')
            ->where('hidden', 0)
            ->where('crawl', 1)
            ->has('chapters');

        $hotComics = (clone $baseQuery)
            ->where('is_hot', 1)
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        $topComics = Comic::with(['latestChapter' => function ($q) {
            $q->latest();
        }])
            ->where('hidden', 0)
            ->where('crawl', 1)
            ->has('chapters')
            ->orderBy('views', 'desc')
            ->limit(12)
            ->get();

        $newComics = (clone $baseQuery)
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        $comics = (clone $baseQuery)
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('site.home', [
            'hotComics' => $hotComics,
            'topComics' => $topComics,
            'comics'    => $comics,
            'newComics' => $newComics
        ]);
    }
}

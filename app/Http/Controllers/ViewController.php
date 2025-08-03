<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Comment;
use App\Models\StickerType;
use App\Models\ViewHistory;

class ViewController extends Controller
{
    public function show($slug)
    {
        $comic = Comic::with([
            'latestChapter',
            'chapters' => function ($q) {
                $q->orderByDesc('number');
            }
        ])->where('slug', $slug)->firstOrFail();

        // Lấy danh sách truyện mới
        $newComics = Comic::with('latestChapter')
            ->where('hidden', 0)
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        $stickerTypes = StickerType::with('stickers')->get();

        $comments = Comment::with('user')
            ->where('comic_id', $comic->id)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('site.comic.comic', [
            'comic'     => $comic,
            'chapters'  => $comic->chapters,
            'newComics' => $newComics,
            'stickerTypes' => $stickerTypes,
            'comments' => $comments
        ]);
    }

    public function chapter($comicSlug, $chapterSlug)
    {
        $comic = Comic::where('slug', $comicSlug)->firstOrFail();

        $chapter = Chapter::with('pages')
            ->where('comic_id', $comic->id)
            ->where('slug', $chapterSlug)
            ->firstOrFail();

        $chapters = Chapter::where('comic_id', $comic->id)
            ->orderBy('number', 'asc')
            ->get();

        $prevChapter = $chapters->where('number', '<', $chapter->number)->sortByDesc('number')->first();
        $nextChapter = $chapters->where('number', '>', $chapter->number)->sortBy('number')->first();

        $stickerTypes = StickerType::with('stickers')->get();

        $comments = Comment::with('user')
            ->where('chapter_id', $chapter->id)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        // Lưu lịch sử đọc
        ViewHistory::create([
            'comic_id'   => $comic->id,
            'chapter_id' => $chapter->id,
            'user_id'    => auth()->check() ? auth()->id() : null,
        ]);

        return view('site.comic.chapter', [
            'comic' => $comic,
            'chapter' => $chapter,
            'prevChapter' => $prevChapter,
            'nextChapter' => $nextChapter,
            'chapters' => $chapters,
            'stickerTypes' => $stickerTypes,
            'comments' => $comments
        ]);
    }
}

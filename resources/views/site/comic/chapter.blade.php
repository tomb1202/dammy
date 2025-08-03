@extends('site.master')

@section('head')
    <title>{{ $comic->title }} - Chapter {{ $chapter->number }} - Đọc truyện tranh tại Mehentai</title>
    <meta name="description"
        content="Chapter {{ $chapter->number }} của {{ $comic->title }} - {{ Str::limit(strip_tags($comic->description), 150) }}">
    <meta name="keywords"
        content="{{ $comic->title }}, chapter {{ $chapter->number }}, đọc truyện {{ $comic->title }}, {{ implode(',', $comic->categories->pluck('name')->toArray()) }}">

    <meta property="og:image" content="{{ $comic->url_image }}">
    <meta property="og:title" content="{{ $comic->title }} - Chapter {{ $chapter->number }}">
    <meta property="og:site_name" content="Mehentai">
    <meta property="og:url"
        content="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $chapter->slug]) }}">
    <meta property="og:type" content="article">
    <meta property="og:description"
        content="Đọc {{ $comic->title }} - Chapter {{ $chapter->number }} tại Mehentai. {{ Str::limit(strip_tags($comic->description), 150) }}">

    <meta itemprop="name" content="{{ $comic->title }} - Chapter {{ $chapter->number }}">
    <meta itemprop="description"
        content="Chapter {{ $chapter->number }} của {{ $comic->title }} - {{ Str::limit(strip_tags($comic->description), 150) }}">

    <link rel="canonical"
        href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $chapter->slug]) }}">

    <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "Chapter",
              "name": "{{ $comic->title }} - Chapter {{ $chapter->number }}",
              "url": "{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $chapter->slug]) }}",
              "partOfBook": {
                "@type": "Book",
                "name": "{{ $comic->title }}",
                "url": "{{ route('comic.show', $comic->slug) }}"
              },
              "position": {{ $chapter->number }},
              "datePublished": "{{ \Carbon\Carbon::parse($chapter->crawl_updated_at)->toIso8601String() }}",

              "image": [
                @foreach ($chapter->pages as $page)
                  "{{ asset('storage/images/chapters/' . $page->image) }}"@if(!$loop->last),@endif
                @endforeach
              ]
            }
            </script>
@endsection

@section('main')
    <div class="site-content content-chapter">
        <div class="container">
            <div class="row">
                <div class="main-col col-md-12 col-sm-12 sidebar-hidden">
                    <h1 id="chapter-heading">{{ $comic->title }} - {{ $chapter->title }}</h1>

                    <div class="main-col-inner">
                        <div class="c-blog-post">
                            <div class="entry-header header" id="manga-reading-nav-head" data-position="header"
                                data-chapter="chapter-{{ $chapter->number }}" data-id="{{ $chapter->id }}"
                                data-sid="{{ $comic->id }}">
                                <div class="wp-manga-nav">
                                    <div class="entry-header_wrap">
                                        <div class="c-breadcrumb-wrapper">
                                            <ol class="breadcrumb">
                                                <li><a href="{{ url('/') }}">Trang Chủ</a></li>
                                                <li>
                                                    <a
                                                        href="{{ route('comic.show', $comic->slug) }}">{{ $comic->title }}</a>
                                                </li>
                                                <li class="active">Chapter {{ $chapter->number }}</li>
                                            </ol>
                                        </div>

                                        <div class="alert alert-info mrb10 hidden-xs hidden-sm">
                                            <i class="icon ion-md-alert"></i> <em>Sử dụng mũi tên trái (←) hoặc phải (→) để
                                                chuyển chapter</em>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="short-link entry-header">
                                <div class="wp-manga-nav">
                                    <div class="entry-header_wrap">
                                        <div class="select-view nav-links">
                                            <a href="{{ url('/') }}" class="btn">
                                                <i class="icon ion-md-home"></i>
                                            </a>
                                            <a href="{{ route('comic.show', $comic->slug) }}" class="btn">
                                                <i class="icon ion-md-information-circle"></i>
                                            </a>

                                            <!-- select chapter -->
                                            <div class="chapters_selectbox_holder" data-manga="{{ $comic->id }}"
                                                data-chapter="chapter-{{ $chapter->number }}">
                                                <div class="c-selectpicker selectpicker_chapter">
                                                    <label>
                                                        <select class="selectpicker single-chapter-select"
                                                            onchange="window.location.href=this.options[this.selectedIndex].getAttribute('data-redirect')">
                                                            @foreach ($chapters as $c)
                                                                <option value="chapter-{{ $c->number }}"
                                                                    data-redirect="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $c->slug]) }}"
                                                                    {{ $c->id === $chapter->id ? 'selected' : '' }}>
                                                                    Chapter {{ $c->number }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="select-pagination">
                                            <div class="nav-links">
                                                <i class="mobile-nav-btn icon ion-md-menu"></i>

                                                {{-- Nút Previous --}}
                                                @if (isset($prevChapter))
                                                    <div class="nav-previous">
                                                        <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $prevChapter->slug]) }}"
                                                            class="btn prev_page" title="{{ $prevChapter->title }}">
                                                            <i class="icon ion-md-arrow-back"></i>
                                                        </a>
                                                    </div>
                                                @endif

                                                {{-- Nút Next --}}
                                                @if (isset($nextChapter))
                                                    <div class="nav-next">
                                                        <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $nextChapter->slug]) }}"
                                                            class="btn next_page" title="{{ $nextChapter->title }}">
                                                            <i class="icon ion-md-arrow-forward"></i>
                                                        </a>
                                                    </div>
                                                @endif

                                                {{-- Nút Back to Top --}}
                                                <div class="nav-back-top">
                                                    <a href="javascript:void(0);" class="btn btn-back-top" title="Back top">
                                                        <i class="icon ion-md-arrow-up"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="entry-content">
                                <div class="entry-content_wrap">
                                    <div class="reading-content" id="chapter_content">

                                        @foreach ($chapter->pages as $index => $page)
                                            <div class="page-break ">
                                                @php
                                                    $imageSrc =
                                                        empty($page->image) || $page->image === 'noimage.png'
                                                            ? $page->url_image
                                                            : asset('storage/images/chapters/' . $page->image);
                                                @endphp

                                                <img id="image-{{ $index }}" src="{{ $imageSrc }}"
                                                    class="chapter-img img-responsive" loading="lazy" width="800px"
                                                    height="400px" alt="truyen-thong-cua-ngoi-lang-ky-la-chap-1-0">

                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>

                            <div class="entry-header footer" id="manga-reading-nav-foot" data-position="footer"
                                data-id="{{ $chapter->id }}">
                                <div class="wp-manga-nav">
                                    <div class="entry-header_wrap">
                                        <div class="select-view nav-links">
                                            <a href="{{ url('/') }}" class="btn">
                                                <i class="icon ion-md-home"></i>
                                            </a>
                                            <a href="{{ route('comic.show', $comic->slug) }}" class="btn">
                                                <i class="icon ion-md-information-circle"></i>
                                            </a>

                                            <!-- select chapter -->
                                            <div class="chapters_selectbox_holder" data-manga="{{ $comic->id }}"
                                                data-chapter="chapter-{{ $chapter->number }}"
                                                data-vol="{{ $chapter->id }}" data-type="manga" data-style="paged">

                                                <div class="c-selectpicker selectpicker_chapter"
                                                    for="volume-id-{{ $chapter->id }}">
                                                    <label>
                                                        <select class="selectpicker single-chapter-select"
                                                            onchange="window.location.href=this.options[this.selectedIndex].getAttribute('data-redirect')">

                                                            @foreach ($chapters as $ch)
                                                                <option class="short"
                                                                    value="chapter-{{ $ch->number }}"
                                                                    data-redirect="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $ch->slug]) }}"
                                                                    {{ $ch->id === $chapter->id ? 'selected' : '' }}>
                                                                    Chapter {{ $ch->number }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="select-pagination">
                                            <div class="nav-links">
                                                <i class="mobile-nav-btn icon ion-md-menu"></i>

                                                {{-- Nút Previous --}}
                                                @if (isset($prevChapter))
                                                    <div class="nav-previous">
                                                        <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $prevChapter->slug]) }}"
                                                            class="btn prev_page" title="{{ $prevChapter->title }}">
                                                            <i class="icon ion-md-arrow-back"></i>
                                                        </a>
                                                    </div>
                                                @endif

                                                {{-- Nút Next --}}
                                                @if (isset($nextChapter))
                                                    <div class="nav-next">
                                                        <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $nextChapter->slug]) }}"
                                                            class="btn next_page" title="{{ $nextChapter->title }}">
                                                            <i class="icon ion-md-arrow-forward"></i>
                                                        </a>
                                                    </div>
                                                @endif

                                                {{-- Nút Back to Top --}}
                                                <div class="nav-back-top">
                                                    <a href="javascript:void(0);" class="btn btn-back-top" title="Back top">
                                                        <i class="icon ion-md-arrow-up"></i>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div id="binhluan">
                                <div id="binhluan_section">
                                    <div class="c-blog__heading style-2 font-heading">
                                        <h2 class="h4"><i class="icon ion-md-star"></i>Bình luận (<span
                                                class="colorblue" id="kountcmt">0</span>)</h2>
                                    </div>
                                    <input hidden="" value="https://Mehentai.nl/formReplyComment"
                                        class="url-form-comment">
                                    <div class="wrap-form-binhluan">
                                        <div class="emo-box">
                                            @foreach ($stickerTypes as $type)
                                                <div class="dropdown emo-tab">
                                                    <button id="{{ $type->slug }}" type="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                        class="emo-item">
                                                        <img src="{{ url('assets/') . $type->stickers->first()->image }}"
                                                            alt="{{ $type->slug }}">
                                                    </button>
                                                    <div aria-labelledby="{{ $type->slug }}"
                                                        class="dropdown-menu emo-menu">
                                                        <div class="emo-expand">
                                                            @foreach ($type->stickers as $sticker)
                                                                <button class="emo-item">
                                                                    <img class="lazyload"
                                                                        data-src="{{ url('assets/') . $sticker->image }}"
                                                                        alt="{{ url('assets/') . $sticker->image }}">
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div id="cmtlid" name="cmtlid" contenteditable="true"
                                            class="tiny comment_content" cols="40" rows="4" maxlength="500"
                                            placeholder="Nội dung thần thức..."></div>
                                        <textarea style="display: none;" id="hrcmt" name="hrcmt" class="comment_content"></textarea>
                                        <div class="binhluan-button">
                                            <div class="hint">
                                                Không click vào link trong comment hay làm theo lời của bọn chó spam comment
                                                nha!
                                            </div>
                                            <button onclick="alert('You need to login to use this function!');"
                                                type="button" class="btn-button">Bình Luận <i class="fa fa-paper-plane"
                                                    aria-hidden="true"></i></button>
                                            <br class="clear">
                                        </div>
                                    </div>
                                    <div class="wrap-list-binhluan">
                                        <div id="comments_load" class="list_comment">
                                            <ul class="list_comment">
                                                @foreach ($comments as $comment)
                                                    <li class="comment_item">
                                                        <a class="wrap-avatar">
                                                            <img src="{{ asset('assets/images/avatar/default.png') }} "
                                                                alt="{{ $comment->user->name }}">
                                                        </a>
                                                        <div class="box-content">
                                                            <strong>{{ $comment->user->name }}</strong>
                                                            <time
                                                                class="time">{{ $comment->created_at->diffForHumans() }}</time>
                                                            <div class="comment-content">
                                                                @if ($comment->chapter_id)
                                                                    <span class="cmchapter">Chapter
                                                                        {{ $comment->chapter_id }}</span>
                                                                @endif
                                                                {!! nl2br(e($comment->content)) !!}

                                                                {{-- Hiển thị sticker nếu có --}}
                                                                @if (!empty($comment->sticker_path))
                                                                    <img class="lazyload"
                                                                        src="{{ asset($comment->sticker_path) }}"
                                                                        alt="sticker">
                                                                @endif

                                                                <div class="comment-button">
                                                                    <button class="btn-button btn-reply"
                                                                        data-story-id="{{ $comic->id }}"
                                                                        data-parent-id="{{ $comment->id }}"
                                                                        data-chapter-id="{{ $comment->chapter_id ?? 0 }}">
                                                                        <i class="fa fa-reply"></i> Reply
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="site-content"> -->
@endsection

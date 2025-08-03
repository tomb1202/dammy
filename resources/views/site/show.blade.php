@extends('site.master')

@section('head')
    <title>Tìm kiếm từ khoá: {{ $keyword ?? '' }} - Mehentai</title>
    <meta name="description" content="Tìm kiếm từ khoá: {{ $keyword ?? '' }} - Mehentai">
    <meta name="robots" content="index, follow">
    <meta name="googlebot-news" content="index,follow">
    <meta itemprop="name" content="Tìm kiếm từ khoá: {{ $keyword ?? '' }} - Mehentai">
    <meta itemprop="description" content="Tìm kiếm từ khoá: {{ $keyword ?? '' }} - Mehentai">

    <meta itemprop="image" content="{{ sourceSetting($settings['logo']) }}">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:title" content="Tìm kiếm từ khoá: {{ $keyword ?? '' }} - Mehentai">
    <meta property="og:description" content="Tìm kiếm từ khoá: {{ $keyword ?? '' }} - Mehentai">

    <meta content={{ sourceSetting($settings['logo']) }} property="og:image">
    <meta content={{ sourceSetting($settings['logo']) }} name="twitter:image">

    <meta property="og:url" content="{{ route('search') }}?q={{ $keyword ?? '' }}">
    <link rel="canonical" href="{{ route('search') }}?q={{ $keyword ?? '' }}">
    <link rel="next" href="{{ route('search') }}?q={{ $keyword ?? '' }}?page=2">
    <link rel="prev" href="{{ route('search') }}?q={{ $keyword ?? '' }}">
    <link rel="stylesheet" href="{{ url('/assets/css/chapter.css') }}" type="text/css">
@endsection

@section('main')
    <div class="site-content content-chapter">
        <div class="container">
            <div class="row">
                <div class="main-col col-md-12 col-sm-12 sidebar-hidden">
                    <h1 id="chapter-heading">{{ $comic->title }} - Chapter {{ $chapter->number }}</h1>

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
                                                    <a href="{{ route('comic.show', ['slug' => $comic->slug]) }}">
                                                        {{ $comic->title }}
                                                    </a>
                                                </li>
                                                <li class="active">Chapter {{ $chapter->number }}</li>
                                            </ol>
                                        </div>

                                        <div class="alert alert-info mrb10 hidden-xs hidden-sm">
                                            <i class="icon ion-md-alert"></i>
                                            <em>Sử dụng mũi tên trái (←) hoặc phải (→) để chuyển chapter</em>
                                        </div>
                                    </div>
                                </div>


                                <div class="entry-header_wrap">
                                    <div class="select-pagination">
                                        <div class="nav-links">
                                            <i class="mobile-nav-btn icon ion-md-menu"></i>

                                            <div class="nav-previous">
                                                @if ($prevChapter)
                                                    <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $prevChapter->slug]) }}"
                                                        class="btn prev_page" title="Chapter {{ $prevChapter->number }}">
                                                        <i class="icon ion-md-arrow-back"></i>
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="nav-back-top">
                                                <a href="javascript:void(0);" class="btn btn-back-top" title="Back top"
                                                    style="display: none;">
                                                    <i class="icon ion-md-arrow-up"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="short-link entry-header">
                                <div class="wp-manga-nav">

                                    <div class="entry-header_wrap">
                                        <div class="select-view nav-links">
                                            <a href="/" class="btn">
                                                <i class="icon ion-md-home"></i>
                                            </a>
                                            <a href="{{ route('comic.show', ['slug' => $comic->slug]) }}" class="btn">
                                                <i class="icon ion-md-information-circle"></i>
                                            </a>

                                            <!-- select chapter -->
                                            <div class="chapters_selectbox_holder" data-type="manga" data-style="paged">
                                                <!-- place holder -->
                                                <div class="c-selectpicker selectpicker_chapter">
                                                    <label>
                                                        <select class="selectpicker single-chapter-select"
                                                            onchange="if(this.value) window.location.href=this.value;">
                                                            @foreach ($chapters->sortByDesc('number') as $ch)
                                                                <option class="short"
                                                                    value="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $ch->slug]) }}"
                                                                    {{ $chapter->id === $ch->id ? 'selected' : '' }}>
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

                                                <!-- Nút Previous -->
                                                <div class="nav-previous">
                                                    @if ($prevChapter)
                                                        <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $prevChapter->slug]) }}"
                                                            class="btn prev_page"
                                                            title="Chapter {{ $prevChapter->number }}">
                                                            <i class="icon ion-md-arrow-back"></i>
                                                        </a>
                                                    @endif
                                                </div>

                                                <!-- Nút Back to top -->
                                                <div class="nav-back-top">
                                                    <a href="javascript:void(0);" class="btn btn-back-top" title="Back top"
                                                        style="display: none;">
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
                                            @php
                                                $imagePath =
                                                    empty($page->image) || $page->image === 'noimage.png'
                                                        ? $page->url_image
                                                        : asset('storage/images/chapters/' . $page->image);
                                            @endphp

                                            <div class="page-break">
                                                <img id="image-{{ $index }}" src="{{ $imagePath }}"
                                                    class="chapter-img img-responsive" loading="lazy" width="800px"
                                                    height="400px"
                                                    alt="{{ $comic->slug }}-chap-{{ $chapter->number }}-{{ $index }}">
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>

                            <div class="entry-header footer" id="manga-reading-nav-foot" data-position="footer"
                                data-id="454">
                                <div class="wp-manga-nav">
                                    <div class="entry-header_wrap">
                                        <div class="select-view nav-links">
                                            <a href="/" class="btn">
                                                <i class="icon ion-md-home"></i>
                                            </a>
                                            <a href="{{ route('comic.show', ['slug' => $comic->slug]) }}" class="btn">
                                                <i class="icon ion-md-information-circle"></i>
                                            </a>

                                            <!-- select chapter -->
                                            <div class="chapters_selectbox_holder" data-type="manga" data-style="paged">
                                                <!-- place holder -->
                                                <div class="c-selectpicker selectpicker_chapter">
                                                    <label>
                                                        <select class="selectpicker single-chapter-select"
                                                            onchange="if(this.value) window.location.href=this.value;">
                                                            @foreach ($chapters->sortByDesc('number') as $ch)
                                                                <option class="short"
                                                                    value="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $ch->slug]) }}"
                                                                    {{ $chapter->id === $ch->id ? 'selected' : '' }}
                                                                    data-limit="40"
                                                                    data-redirect="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $ch->slug]) }}"
                                                                    data-navigation="chapter={{ $ch->slug }}&manga-paged=1&style=paged&action=chapter_navigate_page">
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

                                                <!-- Previous chapter -->
                                                <div class="nav-previous">
                                                    @if ($prevChapter)
                                                        <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $prevChapter->slug]) }}"
                                                            class="btn prev_page"
                                                            title="Chapter {{ $prevChapter->number }}">
                                                            <i class="icon ion-md-arrow-back"></i>
                                                        </a>
                                                    @endif
                                                </div>

                                                <!-- Back to top -->
                                                <div class="nav-back-top">
                                                    <a href="javascript:void(0);" class="btn btn-back-top"
                                                        title="Back top" style="display: none;">
                                                        <i class="icon ion-md-arrow-up"></i>
                                                    </a>
                                                </div>

                                                <!-- Next chapter -->
                                                <div class="nav-next">
                                                    @if ($nextChapter)
                                                        <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $nextChapter->slug]) }}"
                                                            class="btn next_page"
                                                            title="Chapter {{ $nextChapter->number }}">
                                                            <i class="icon ion-md-arrow-forward"></i>
                                                    @endif

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

                <script>
                    const story = {
                        id: {{ $comic->id }},
                        image: "{{ $comic->image && $comic->image !== 'noimage.png'
                            ? asset('storage/images/covers/' . $comic->image)
                            : $comic->url_image }}",
                        name: @json($comic->title),
                        chapterName: @json($chapter->title ?? 'Chapter ' . $chapter->number),
                        chapterUrl: window.location.href,
                        chapterId: {{ $chapter->id }},
                        url: "{{ route('comic.show', ['slug' => $comic->slug]) }}"
                    };
                </script>

            </div>
        </div>
    </div>
@endsection

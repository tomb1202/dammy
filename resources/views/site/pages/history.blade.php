@extends('site.master')

@section('head')
    <title>Lịch sử đọc - Mehentai</title>
    <meta name="description" content="Lịch sử đọc - Mehentai">
    <meta name="robots" content="index, follow">
    <meta name="googlebot-news" content="index,follow">
    <meta itemprop="name" content="Lịch sử đọc - Mehentai">
    <meta itemprop="description" content="Lịch sử đọc - Mehentai">

    <meta itemprop="image" content="{{ sourceSetting($settings['logo']) }}">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:title" content="Lịch sử đọc - Mehentai">
    <meta property="og:description" content="Lịch sử đọc - Mehentai">

    <meta content={{ sourceSetting($settings['logo']) }} property="og:image">
    <meta content={{ sourceSetting($settings['logo']) }} name="twitter:image">

    <meta property="og:url" content="{{ route('site.history') }}">
    <link rel="canonical" href="{{ route('site.history') }}">
    <link rel="next" href="{{ route('site.history') }}?page=2">
    <link rel="prev" href="{{ route('site.history') }}">
@endsection

@section('main')
    <div class="site-content">
        <div class="c-breadcrumb-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <ol class="breadcrumb">
                            <li>
                                <a href="{{ url('/') }}">Trang Chủ </a>
                            </li>
                            <li>
                                <a href="{{ route('site.history') }}">Lịch Sử</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-category mt-4">
            <div class="container">
                <div class="row ">
                    <div class="main-col col-md-8 col-sm-8">
                        <div class="main-col-inner">
                            <div class="tab-item-historyzz">
                                <div class="row">

                                    <div class="tab-item-historyzz">
                                        <div class="row">
                                            @foreach ($comics as $comic)
                                                <div class="col-md-4">
                                                    <div class="history-content">
                                                        <div class="item-thumb">
                                                            @php
                                                                $imageSrc =
                                                                    empty($comic->image) ||
                                                                    $comic->image === 'noimage.png'
                                                                        ? $comic->url_image
                                                                        : asset(
                                                                            'storage/images/covers/' . $comic->image,
                                                                        );
                                                            @endphp

                                                            <a title="{{ $comic->title }}"
                                                                href="{{ route('comic.show', ['slug' => $comic->slug]) }}">
                                                                <img width="100" height="60" class="img-responsive"
                                                                    src="{{ $imageSrc }}"
                                                                    onerror="this.onerror=null;this.src='{{ url('assets/img/default.jpg') }}';"
                                                                    alt="{{ $comic->title }}">
                                                            </a>

                                                        </div>
                                                        <div class="item-infor">
                                                            <div class="settings-title">
                                                                <h3 class="line-2 font-16">
                                                                    <a title="{{ $comic->title }}"
                                                                        href="{{ route('comic.show', ['slug' => $comic->slug]) }}">
                                                                        {{ $comic->title }}
                                                                    </a>
                                                                </h3>
                                                            </div>
                                                            @if ($comic->latestChapter)
                                                                <div class="chapter">
                                                                    <span class="chap">
                                                                        <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $comic->latestChapter->slug]) }}"
                                                                            class="btn-link">
                                                                            {{ $comic->latestChapter->title ?? 'Chapter mới nhất' }}
                                                                        </a>
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="action">
                                                            <span class="view pull-right">
                                                                <a class="visited-remove" href="#"
                                                                    data-id="{{ $comic->id }}">
                                                                    <i class="icon ion-ios-close"></i>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    @if (isset($categories) && count($categories) > 0)
                        <div class="sidebar-col col-md-4 col-sm-4">
                            <div class="main-sidebar sidebar-genres" role="complementary">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="widget-heading font-nav">
                                            <h5 class="heading">Thể Loại</h5>
                                        </div>
                                        <div class="widget-content">
                                            <ul class="list-unstyled row genres">
                                                @foreach ($categories as $category)
                                                    <li class="col-6 col-sm-6">
                                                        <a
                                                            href="{{ route('site.category', ['categorySlug' => $category->slug]) }}">
                                                            <i class="icon ion-md-arrow-dropright"></i>
                                                            {{ $category->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

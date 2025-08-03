@extends('site.master')

@section('head')
    <title>Truyện Manga hay nhất 2025 - Đọc truyện tranh Nhật Bản</title>
    <meta name="description" content="Tổng hợp truyện manga (truyện tranh Nhật Bản) chất lượng cao, vietsub đầy đủ. Cập nhật truyện mới nhanh chóng, đọc miễn phí.">
    <meta name="robots" content="index, follow">
    <meta name="googlebot-news" content="index,follow">

    <meta itemprop="name" content="Truyện Manga hay nhất 2025 - Đọc truyện tranh Nhật Bản">
    <meta itemprop="description" content="Tổng hợp truyện manga (truyện tranh Nhật Bản) chất lượng cao, vietsub đầy đủ. Cập nhật truyện mới nhanh chóng, đọc miễn phí.">
    <meta itemprop="image" content="{{ sourceSetting($settings['logo']) }}">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:title" content="Truyện Manga hay nhất 2025 - Đọc truyện tranh Nhật Bản">
    <meta property="og:description" content="Tổng hợp truyện manga (truyện tranh Nhật Bản) chất lượng cao, vietsub đầy đủ. Cập nhật truyện mới nhanh chóng, đọc miễn phí.">
    <meta property="og:image" content="{{ sourceSetting($settings['logo']) }}">
    <meta name="twitter:image" content="{{ sourceSetting($settings['logo']) }}">

    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">

    @if ($comics->currentPage() < $comics->lastPage())
        <link rel="next" href="{{ $comics->url($comics->currentPage() + 1) }}">
    @endif

    @if ($comics->currentPage() > 1)
        <link rel="prev" href="{{ $comics->url($comics->currentPage() - 1) }}">
    @endif
@endsection


@section('main')
    <div class="site-content">
    <div class="c-breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{ url('/') }}">Trang Chủ</a>
                        </li>
                        <li>
                            <a href="{{ route('manga') }}">Manga</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="page-category">
        <div class="container">
            <div class="row">
                <div class="main-col col-md-8 col-sm-8">
                    <div class="main-col-inner">
                        <div class="c-page">
                            <div class="entry-header">
                                <div class="entry-header_wrap">
                                    <div class="entry-title">
                                        <h1 class="item-title h4">Truyện Manga</h1>
                                        <p>Tổng hợp truyện manga (truyện tranh Nhật Bản) chất lượng cao, vietsub đầy đủ. Cập nhật truyện mới nhanh chóng, đọc miễn phí.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="c-page__content">
                                <div class="tab-wrap">
                                    <div class="c-blog__heading style-2 font-heading">
                                        <div class="h4">
                                            <i class="icon ion-md-star"></i>
                                            Truyện Manga
                                        </div>
                                        <div class="c-nav-tabs">
                                            <span>Sắp xếp</span>
                                            <ul class="c-tabs-content">
                                                <li class="{{ $m_orderby === 'latest' ? 'active' : '' }}">
                                                    <a href="?m_orderby=latest">Mới Nhất</a>
                                                </li>
                                                <li class="{{ $m_orderby === 'rating' ? 'active' : '' }}">
                                                    <a href="?m_orderby=rating">Đánh giá cao</a>
                                                </li>
                                                <li class="{{ $m_orderby === 'views' ? 'active' : '' }}">
                                                    <a href="?m_orderby=views">Xem Nhiều</a>
                                                </li>
                                                <li class="{{ $m_orderby === 'new' ? 'active' : '' }}">
                                                    <a href="?m_orderby=new">Mới</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content-wrap">
                                    <div class="listing row px-2 cdhihi">
                                        @foreach ($comics as $comic)
                                            <div class="col-6 col-md-4 col-lg-3 badge-pos-1 px-2">
                                                <div class="page-item-detail">
                                                    <div class="item-thumb hover-details c-image-hover">
                                                        @php
                                                            $imageSrc = empty($comic->image) || $comic->image === 'noimage.png'
                                                                ? $comic->url_image
                                                                : asset('storage/images/covers/' . $comic->image);
                                                        @endphp
                                                        <a href="{{ route('comic.show', $comic->slug) }}"
                                                            title="{{ $comic->title }}">
                                                            <img class="img-responsive" src="{{ $imageSrc }}"
                                                                onerror="this.onerror=null;this.src='{{ url('assets/img/default.jpg') }}';"
                                                                alt="{{ $comic->title }}">
                                                        </a>
                                                    </div>
                                                    <div class="item-summary">
                                                        <div class="post-title font-title">
                                                            <h3 class="line-2">
                                                                <a href="{{ route('comic.show', $comic->slug) }}">
                                                                    {{ $comic->title }}
                                                                </a>
                                                            </h3>
                                                        </div>
                                                        <div class="list-chapter ghihi">
                                                            @if ($comic->latestChapter)
                                                                <div class="chapter-item">
                                                                    <span class="chapter font-meta">
                                                                        <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $comic->latestChapter->slug]) }}"
                                                                            class="btn-link">
                                                                            {{ $comic->latestChapter->title }}
                                                                        </a>
                                                                    </span>
                                                                    <span class="post-on font-meta">
                                                                        {{ \Carbon\Carbon::parse($comic->latestChapter->crawl_updated_at ?? $comic->latestChapter->updated_at)->diffForHumans() }}
                                                                    </span>
                                                                </div>
                                                            @else
                                                                <div class="chapter-item">
                                                                    <span class="font-meta text-muted">Chưa có chương</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <br><br>
                                        {{ $comics->appends(request()->input())->links('site.pagination') }}
                                        <br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (isset($newComics) && count($newComics) > 0)
                        <div class="sidebar-col col-md-4 col-sm-4">
                            <div class="popular">
                                <div class="widget-heading">
                                    <h5 class="heading">Truyện Mới</h5>
                                </div>
                                <div class="widget-content">
                                    @foreach ($newComics as $comic)
                                        <div class="popular-item-wrap">
                                            <div class="popular-img widget-thumbnail c-image-hover">
                                                <a title="{{ $comic->title }}"
                                                    href="{{ route('comic.show', ['slug' => $comic->slug]) }}">
                                                    <img width="75" height="106" class="img-responsive lazyload"
                                                        src="{{ asset('storage/images/covers/' . $comic->image) }}"
                                                        onerror="this.onerror=null;this.src='{{ url('assets/img/default.jpg') }}';"
                                                        alt="{{ $comic->slug }}">
                                                </a>
                                            </div>
                                            <div class="popular-content">
                                                <h5 class="widget-title">
                                                    <a title="{{ $comic->title }}"
                                                        href="{{ route('comic.show', ['slug' => $comic->slug]) }}">
                                                        {{ $comic->title }}
                                                    </a>
                                                </h5>
                                                <div class="list-chapter yhihi">
                                                    @if ($comic->latestChapter)
                                                        <div class="chapter-item">
                                                            <span class="chapter font-meta">
                                                                <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $comic->latestChapter->slug]) }}"
                                                                    class="btn-link">
                                                                    {{ $comic->latestChapter->title }}
                                                                </a>
                                                            </span>
                                                            <span class="post-on font-meta">
                                                                {{ \Carbon\Carbon::parse($comic->latestChapter->crawl_updated_at ?? $comic->latestChapter->updated_at)->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

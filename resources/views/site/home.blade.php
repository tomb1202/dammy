@extends('site.master')

@section('head')
    <title>{{ $settings['title'] }}</title>
    <meta name="description" content="{!! $settings['description'] !!}">
    <meta name="robots" content="index, follow">
    <meta name="googlebot-news" content="index,follow">
    <meta itemprop="name" content="{{ $settings['title'] }}">
    <meta itemprop="description" content="{!! $settings['description'] !!}">

    <meta itemprop="image" content="{{ sourceSetting($settings['logo']) }}">
    <!--Meta Facebook Page Other-->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:title" content="{{ $settings['title'] }}">
    <meta property="og:description" content="{!! $settings['description'] !!}">

    <meta content={{ sourceSetting($settings['logo']) }} property="og:image">
    <meta content={{ sourceSetting($settings['logo']) }} name="twitter:image">

    <meta property="og:url" content="{{ url('/') }}">
    <link rel="canonical" href="{{ url('/') }}">
    <link rel="next" href="{{ url('/') }}">
    <link rel="prev" href="{{ url('/') }}">

    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "Mehentai",
          "url": "{{url('/')}}",
          "potentialAction": {
            "@type": "SearchAction",
            "target": "{{url('/')}}/tim-kiem?s={search_term_string}",
            "query-input": "required name=search_term_string"
          }
        }
    </script>
@endsection
@section('main')
    @if (isset($hotComics) && count($hotComics) > 0)
        <div class="slide-home ahihi">
            <div class="container">
                <div class="row">
                    <div class="c-blog__heading style-2 font-heading ">
                        <h2 class="h4"><i class="icon ion-md-flash"></i>
                            TRUYỆN HOT
                        </h2>
                    </div>
                    <div class="row">
                        @foreach ($hotComics as $comic)
                            <div class="item col-4 col-md-2">
                                <div class="img-item">
                                    @php
                                        $imageSrc =
                                            empty($comic->image) || $comic->image === 'noimage.png'
                                                ? $comic->url_image
                                                : asset('storage/images/covers/' . $comic->image);
                                    @endphp

                                    <a href="{{ route('comic.show', ['slug' => $comic->slug]) }}">
                                        <img width="175" height="211" src="{{ $imageSrc }}"
                                            onerror="this.onerror=null;this.src='{{ url('assets/img/default.jpg') }}';"
                                            alt="{{ $comic->title }}">
                                        <span class="chapter font-meta"></span>
                                    </a>

                                    @if ($comic->latestChapter)
                                        <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $comic->latestChapter->slug]) }}"
                                            class="btn-link">
                                            Chapter {{ $comic->latestChapter->number ?? '' }}
                                        </a>
                                    @endif
                                </div>

                                <div class="info-item">
                                    <div class="line-2 font-weight-bold">
                                        <a href="{{ route('comic.show', ['slug' => $comic->slug]) }}">
                                            {{ $comic->title }}
                                        </a>
                                    </div>
                                    <div class="list-chapter">
                                        <div class="chapter-item">
                                            @if ($comic->latestChapter && $comic->latestChapter->created_at)
                                                <span class="post-on font-meta">

                                                    @if ($comic->latestChapter)
                                                                                {{ \Carbon\Carbon::parse($comic->latestChapter->crawl_updated_at ?? $comic->latestChapter->updated_at)->diffForHumans() }}
                                                                            @else
                                                                                Không có chương nào
                                                                            @endif

                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (isset($topComics) && count($topComics) > 0)
        <div class="slide-home ahihi">
            <div class="container">
                <div class="row">
                    <div class="c-blog__heading style-2 font-heading ">
                        <h2 class="h4"><i class="icon ion-md-flash"></i>
                            Top Ngày
                        </h2>
                    </div>
                    <div id="slide-top" class="cui_vl">
                        @foreach ($topComics as $comic)
                            <div class="item col-4 col-md-2">
                                <div class="img-item">
                                    @php
                                        $imageSrc =
                                            empty($comic->image) || $comic->image === 'noimage.png'
                                                ? $comic->url_image
                                                : asset('storage/images/covers/' . $comic->image);
                                    @endphp

                                    <a href="{{ route('comic.show', ['slug' => $comic->slug]) }}">
                                        <img width="175" height="211" src="{{ $imageSrc }}"
                                            onerror="this.onerror=null;this.src='{{ url('assets/img/default.jpg') }}';"
                                            alt="{{ $comic->title }}">
                                        <span class="chapter font-meta"></span>
                                    </a>

                                    @if (!empty($comic->latestChapter))
                                        <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $comic->latestChapter->slug]) }}"
                                            class="btn-link"> {{ $comic->latestChapter->title }} </a>
                                    @endif
                                </div>
                                <div class="info-item">
                                    <div class="line-2 font-weight-bold">
                                        <a href="{{ route('comic.show', ['slug' => $comic->slug]) }}">
                                            {{ \Illuminate\Support\Str::limit($comic->title, 50) }}
                                        </a>
                                    </div>
                                    <div class="list-chapter">
                                        <div class="chapter-item">
                                            <span class="post-on font-meta">
                                             
                                                @if ($comic->latestChapter)
                                                                                {{ \Carbon\Carbon::parse($comic->latestChapter->crawl_updated_at ?? $comic->latestChapter->updated_at)->diffForHumans() }}
                                                                            @else
                                                                                Không có chương nào
                                                                            @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    @endif

    <div class="site-content">
        <div class="container" style="margin-top: 50px">
            <div class="row ">
                <div class="main-col col-md-8 col-sm-8">
                    <div class="c-blog__heading style-2 font-heading ">
                        <h2 class="h4">
                            <i class="icon ion-md-star"></i>
                            Mới Cập Nhật
                        </h2>
                    </div>
                    <div class="manga-content">
                        <div class="row px-2 list-item">

                            @foreach ($comics as $comic)
                                <div class="col-6 col-md-4 col-lg-3 badge-pos-1 px-2 1 ">
                                    <div class="page-item-detail">
                                        <div class="item-thumb hover-details c-image-hover">
                                            @php
                                                $imageSrc =
                                                    empty($comic->image) || $comic->image === 'noimage.png'
                                                        ? $comic->url_image
                                                        : asset('storage/images/covers/' . $comic->image);
                                            @endphp

                                            <a href="{{ route('comic.show', ['slug' => $comic->slug]) }}"
                                                title="{{ $comic->title }}">
                                                <img width="175" height="238" class="img-responsive"
                                                    src="{{ $imageSrc }}"
                                                    onerror="this.onerror=null;this.src='{{ url('assets/img/default.jpg') }}';"
                                                    title="{{ $comic->title }}" alt="{{ $comic->slug }}">
                                            </a>

                                        </div>
                                        <div class="item-summary">
                                            <div class="post-title font-title">
                                                <h3 class="line-2 pt-2">
                                                    <a href="{{ route('comic.show', ['slug' => $comic->slug]) }}">
                                                        {{ $comic->title }}
                                                    </a>
                                                </h3>
                                            </div>
                                            <p></p>
                                            <div class="list-chapter">
                                                <div class="chapter-item ">
                                                    @if ($comic->latestChapter)
                                                        <span class="chapter font-meta">
                                                            <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $comic->latestChapter->slug]) }}"
                                                                class="btn-link">
                                                                Chapter {{ $comic->latestChapter->number }}
                                                            </a>
                                                        </span>
                                                        <span class="post-on font-meta">
                                                            @if ($comic->latestChapter)
                                                                                {{ \Carbon\Carbon::parse($comic->latestChapter->crawl_updated_at ?? $comic->latestChapter->updated_at)->diffForHumans() }}
                                                                            @else
                                                                                Không có chương nào
                                                                            @endif
                                                        </span>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-12">
                                {{ $comics->appends(request()->input())->links('site.pagination') }}
                            </div>

                        </div>
                    </div>

                </div>

                @if (isset($newComics) && count($newComics) > 0)
                    <div class="sidebar col-md-4 col-sm-4">
                        <div class="popular">
                            <div class="widget-heading">
                                <h5 class="heading">Truyện Mới</h5>
                            </div>
                            <div class="widget-content">
                                @foreach ($newComics as $comic)
                                    <div class="popular-item-wrap d-flex mb-3">
                                        <div class="popular-img widget-thumbnail c-image-hover mr-2">
                                            @php
                                                $imageSrc =
                                                    empty($comic->image) || $comic->image === 'noimage.png'
                                                        ? $comic->url_image
                                                        : asset('storage/images/covers/' . $comic->image);
                                            @endphp

                                            <a title="{{ $comic->title }}"
                                                href="{{ route('comic.show', ['slug' => $comic->slug]) }}">
                                                <img width="75" height="106" class="img-responsive lazyload"
                                                    src="{{ $imageSrc }}"
                                                    onerror="this.onerror=null;this.src='{{ url('assets/img/default.jpg') }}';"
                                                    alt="{{ $comic->slug }}">
                                            </a>

                                        </div>
                                        <div class="popular-content">
                                            <h5 class="widget-title mb-1">
                                                <a title="{{ $comic->title }}"
                                                    href="{{ route('comic.show', ['slug' => $comic->slug]) }}">
                                                    {{ \Illuminate\Support\Str::limit($comic->title, 50) }}
                                                </a>
                                            </h5>
                                            <div class="list-chapter yhihi">
                                                <div class="chapter-item">
                                                    @if (!empty($comic->latestChapter))
                                                        <a class="btn-link"
                                                            href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $comic->latestChapter->slug]) }}">
                                                            {{ $comic->latestChapter->title }}
                                                        </a>
                                                        <span class="post-on font-meta d-block mt-1">
                                                            @if ($comic->latestChapter)
                                                                                {{ \Carbon\Carbon::parse($comic->latestChapter->crawl_updated_at ?? $comic->latestChapter->updated_at)->diffForHumans() }}
                                                                            @else
                                                                                Không có chương nào
                                                                            @endif
                                                        </span>
                                                    @endif
                                                </div>
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
@endsection

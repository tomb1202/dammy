@extends('site.master')

@section('head')
    <title>{{ $comic->title }} - Đọc truyện tranh tại Mehentai</title>
    <meta name="description"
        content="{{ $comic->description ? Str::limit(strip_tags($comic->description), 150) : 'Đọc truyện tranh ' . $comic->title . ' mới nhất tại Mehentai' }}">
    <meta name="keywords"
        content="{{ $comic->title }}, Mehentai, đọc truyện {{ $comic->title }}, truyện tranh {{ $comic->title }}, {{ implode(',', $comic->categories->pluck('name')->toArray() ?? []) }}">
    <meta property="og:image" content="{{ $comic->url_image }}">
    <meta property="og:title" content="{{ $comic->title }} - Mehentai">
    <meta property="og:site_name" content="Mehentai">
    <meta property="og:url" content="{{ route('comic.show', ['slug' => $comic->slug]) }}">
    <meta property="og:type" content="article">
    <meta property="og:description"
        content="{{ $comic->description ? Str::limit(strip_tags($comic->description), 150) : 'Đọc truyện tranh ' . $comic->title . ' mới nhất tại Mehentai' }}">
    <meta itemprop="name" content="{{ $comic->title }}">
    <meta itemprop="description"
        content="{{ $comic->description ? Str::limit(strip_tags($comic->description), 150) : 'Đọc truyện tranh ' . $comic->title . ' tại Mehentai' }}">

    <link rel="canonical" href="{{ route('comic.show', ['slug' => $comic->slug]) }}">

    <script type="application/ld+json">
{!! json_encode([
    "@context" => "https://schema.org",
    "@type" => "Book",
    "name" => $comic->title,
    "url" => route('comic.show', $comic->slug),
    "description" => Str::limit(strip_tags($comic->description), 200),
    "image" => asset('storage/images/covers/' . $comic->image),
    "author" => [
        "@type" => "Person",
        "name" => $comic->author ?? 'Đang cập nhật',
    ],
    "publisher" => [
        "@type" => "Organization",
        "name" => "Mehentai",
    ],
    "genre" => $comic->categories->pluck('name')->toArray(),
    "aggregateRating" => [
        "@type" => "AggregateRating",
        "ratingValue" => $comic->ratings ?? 4.5,
        "reviewCount" => $comic->votes ?? 744,
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection
@section('main')
    <div class="site-content">
        <div class="profile-manga">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="c-breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li><a href="/">Trang Chủ</a></li>
                                @if ($comic->categories->isNotEmpty())
                                    <li><a
                                            href="{{ route('site.category', ['categorySlug' => $comic->categories->first()->slug]) }}">
                                            {{ $comic->categories->first()->name }}
                                        </a></li>
                                @endif
                                <li>{{ $comic->title }}</li>
                            </ol>
                        </div>

                        <div class="post-title">
                            <h1>{{ $comic->title }}</h1>
                        </div>
                        <div class="tab-summary">
                            <div class="summary_image">

                                @php
                                    $imageSrc =
                                        empty($comic->image) || $comic->image === 'noimage.png'
                                            ? $comic->url_image
                                            : asset('storage/images/covers/' . $comic->image);
                                @endphp

                                <a title="{{ $comic->title }}" href="{{ route('comic.show', ['slug' => $comic->slug]) }}">
                                    <img width="193" height="278" class="img-responsive lazyload"
                                        src="{{ $imageSrc }}"
                                        onerror="this.onerror=null;this.src='{{ url('assets/img/default.jpg') }}';"
                                        alt="{{ $comic->slug }}">
                                </a>

                            </div>
                            <div class="summary_content_wrap">
                                <div class="summary_content">
                                    <div class="post-content">
                                        <div class="post-content_item">
                                            <div class="summary-heading">
                                                <h5>Rating</h5>
                                            </div>
                                            <div class="summary-content vote-details">
                                                {{ $comic->rating ?? 'Chưa có' }} / 5
                                            </div>
                                        </div>

                                        <div class="post-content_item">
                                            <div class="summary-heading">
                                                <h5>Tên khác</h5>
                                            </div>
                                            <div class="summary-content">{{ $comic->alt_name ?? 'Đang cập nhật' }}</div>
                                        </div>

                                        <div class="post-content_item">
                                            <div class="summary-heading">
                                                <h5>Tác giả</h5>
                                            </div>
                                            <div class="summary-content">{{ $comic->author ?? 'Đang cập nhật' }}</div>
                                        </div>

                                        <div class="post-content_item">
                                            <div class="summary-heading">
                                                <h5>View</h5>
                                            </div>
                                            <div class="summary-content">{{ number_format($comic->views) }}</div>
                                        </div>

                                        <div class="post-content_item">
                                            <div class="summary-heading">
                                                <h5>Trạng thái</h5>
                                            </div>
                                            <div class="summary-content">
                                                {{ $comic->status == 'complete' ? 'Hoàn thành' : 'Đang ra' }}
                                            </div>
                                        </div>

                                        <div class="post-content_item">
                                            <div class="summary-heading">
                                                <h5>Thể loại</h5>
                                            </div>
                                            <div class="summary-content">
                                                <div class="genres-content">
                                                    @foreach ($comic->categories as $category)
                                                        <a href="{{ route('site.category', ['categorySlug' => $category->slug]) }}"
                                                            rel="tag">{{ $category->name }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="post-status">
                                        <div class="post-content_item">
                                            <div class="summary-heading">
                                                <h5>Nhóm Dịch</h5>
                                            </div>
                                            <div class="summary-content">
                                                {{ $comic->translation_group ?? 'Đang cập nhật' }}
                                            </div>
                                        </div>

                                        <div class="manga-action">
                                            <div class="count-comment">
                                                <div class="action_icon">
                                                    <a href="#manga-discussion"><i class="icon ion-md-chatbubbles"></i></a>
                                                </div>
                                                <div class="action_detail">
                                                    <span>0 Bình luận</span>
                                                </div>
                                            </div>

                                            <div class="add-bookmark">
                                                <div class="action_icon">
                                                    <a href="#" class="wp-manga-action-button" title="Bookmark">
                                                        <i class="icon ion-ios-bookmark"></i>
                                                    </a>
                                                </div>
                                                <div class="action_detail">
                                                    <span>{{ $comic->follows_count ?? 0 }} người theo dõi</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="init-links" class="nav-links text-center mt-3">
                                            @if ($comic->chapters->isNotEmpty())
                                                <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $comic->chapters->last()->slug]) }}"
                                                    class="c-btn c-btn_style-1">Chap đầu</a>
                                                <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $comic->chapters->first()->slug]) }}"
                                                    class="c-btn c-btn_style-1">Chap cuối</a>
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

        <div class="c-page-content style-1">
            <div class="container">
                <div class="row">
                    <div class="main-col col-md-8 col-sm-8">
                        <div class="c-blog__heading style-2 font-heading">
                            <h2 class="h4">
                                <i class="icon ion-md-star"></i>Nội Dung
                            </h2>
                        </div>

                        <div class="description-summary">
                            <div class="summary__content show-more">
                                <p>...</p>
                            </div>
                        </div>
                        <div class="list-chapter phihi">
                            <div class="c-blog__heading style-2 font-heading">
                                <h4 class="h4"><i class="icon ion-md-star"></i>Danh sách chapter</h4>
                                <a href="#" title="Change Order" class="btn-reverse-order">
                                    <i class="icon ion-md-swap"></i>
                                </a>
                            </div>
                            <ul class="list-item box-list-chapter limit-height">
                                @foreach ($comic->chapters->sortByDesc('number') as $chapter)
                                    <li class="wp-manga-chapter">
                                        <a
                                            href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $chapter->slug]) }}">
                                            Chapter {{ $chapter->number }}
                                        </a>
                                        <span class="number-view">{{ number_format($chapter->views ?? 0) }} views</span>
                                        <span class="chapter-release-date">
                                            {{ \Carbon\Carbon::parse($chapter->crawl_created_at)->diffForHumans() }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="c-chapter-readmore total-view-chapter" data-view="{{ $comic->views ?? 0 }}">
                                <a class="btn btn-link chapter-readmore less-chap">Xem thêm</a>
                            </div>
                        </div>

                        <!-- comments-area -->
                        <div id="binhluan">
                            <div id="binhluan_section">
                                <div class="c-blog__heading style-2 font-heading">
                                    <h2 class="h4"><i class="icon ion-md-star"></i>Bình luận (<span class="colorblue"
                                            id="kountcmt">0</span>)</h2>
                                </div>
                                <input hidden="" value="https://Mehentai.nl/formReplyComment"
                                    class="url-form-comment">
                                <div class="wrap-form-binhluan">
                                    <div class="emo-box">
                                        @foreach ($stickerTypes as $type)
                                            <div class="dropdown emo-tab">
                                                <button id="{{ $type->slug }}" type="button" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false" class="emo-item">
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
                                        <button onclick="alert('You need to login to use this function!');" type="button"
                                            class="btn-button">Bình Luận <i class="fa fa-paper-plane"
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
                                <button class="btn btn-block btn-loadmore-comment" data-offset="10"
                                    data-url="https://Mehentai.nl/loadComment" data-story-id="7086"
                                    data-chapter-id="0">Xem thêm</button>
                            </div>
                        </div>

                        <!-- END comments-area -->


                    </div>
                    <div class="sidebar col-md-4 col-sm-4">
                        <div class="popular">
                            <div class="widget-heading">
                                <h5 class="heading">Truyện Mới</h5>
                            </div>
                            <div class="widget-content">
                                @foreach ($newComics as $comic)
                                    <div class="popular-item-wrap">
                                        <div class="popular-img widget-thumbnail c-image-hover">
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
                                            <h5 class="widget-title">
                                                <a title="{{ $comic->title }}"
                                                    href="{{ route('comic.show', $comic->slug) }}">
                                                    {{ $comic->title }}
                                                </a>
                                            </h5>
                                            <div class="list-chapter yhihi">
                                                @if ($comic->latestChapter)
                                                    <div class="chapter-item">
                                                        <span class="chapter font-meta">
                                                            <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $comic->latestChapter->slug]) }}"
                                                                class="btn-link"> Chapter
                                                                {{ $comic->latestChapter->number }} </a>
                                                        </span>
                                                        <span class="post-on font-meta">
                                                            @if ($comic->latestChapter)
                                                                {{ \Carbon\Carbon::parse($comic->latestChapter->crawl_updated_at ?? $comic->latestChapter->updated_at)->diffForHumans() }}
                                                            @else
                                                                Không có chương nào
                                                            @endif
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

                </div>
            </div>
        </div>
    </div>
@endsection

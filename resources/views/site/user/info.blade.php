@extends('site.master')


@section('head')
    <title>Book Mark</title>
    <meta name="description" content="{!! $settings['description'] !!}">
    <meta name="robots" content="index, follow">
    <meta name="googlebot-news" content="index,follow">
    <meta itemprop="name" content="Book Mark">
    <meta itemprop="description" content="{!! $settings['description'] !!}">

    <meta itemprop="image" content="{{ sourceSetting($settings['logo']) }}">
    <!--Meta Facebook Page Other-->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:title" content="Book Mark">
    <meta property="og:description" content="{!! $settings['description'] !!}">

    <meta content={{ sourceSetting($settings['logo']) }} property="og:image">
    <meta content={{ sourceSetting($settings['logo']) }} name="twitter:image">

    <meta property="og:url" content="{{ url('/') }}">
    <link rel="canonical" href="{{ url('/') }}">
    <link rel="next" href="{{ url('/') }}?page=2">
    <link rel="prev" href="">
@endsection

@section('main')
    <div class="site-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="main-col-inner">
                        <div class="c-blog-post post-5 status-publish hentry">
                            <div class="entry-header">
                                <div class="entry-title">
                                    <h1 class="item-title h2">Cài Đặt</h1>
                                </div>
                                <div class="entry-meta">

                                </div>
                            </div>
                            <div class="entry-content">
                                <div class="row settings-page">
                                    <div class="col-md-3 col-sm-3">
                                        <div class="nav-tabs-wrap">
                                            <ul class="nav nav-tabs">
                                                <li>
                                                    <a href="#list-bookmark" data-toggle="tab">
                                                        <i class="icon ion-ios-bookmark"></i>Truyện theo dõi
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#history" data-toggle="tab">
                                                        <i class="icon ion-md-alarm"></i>Lịch Sử
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#account" data-toggle="tab" class="active">
                                                        <i class="icon ion-md-person"></i>
                                                        Cài Đặt </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-9 col-sm-9">
                                        <div class="tabs-content-wrap">
                                            <div class="tab-content">
                                                <div class="tab-pane" id="list-bookmark">
                                                    <table class="table table-hover list-bookmark">
                                                        <thead>
                                                            <tr>
                                                                <th>Tên truyện</th>
                                                                <th>Cập nhật gần nhất</th>
                                                                <th>Hành động</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($follows as $comic)
                                                                <tr>
                                                                    <td>
                                                                        <div class="mange-name d-flex">
                                                                            <div class="item-thumb mr-3">
                                                                                @php
                                                                                    $imageSrc =
                                                                                        empty($comic->image) ||
                                                                                        $comic->image === 'noimage.png'
                                                                                            ? $comic->url_image
                                                                                            : asset(
                                                                                                'storage/images/covers/' .
                                                                                                    $comic->image,
                                                                                            );
                                                                                @endphp

                                                                                <a href="{{ route('comic.show', ['slug' => $comic->slug]) }}"
                                                                                    title="{{ $comic->title }}">
                                                                                    <img width="120" height="80"
                                                                                        src="{{ $imageSrc }}"
                                                                                        onerror="this.onerror=null;this.src='{{ url('assets/img/default.jpg') }}';"
                                                                                        alt="{{ $comic->title }}">
                                                                                </a>

                                                                            </div>
                                                                            <div class="item-infor">
                                                                                <div class="post-title">
                                                                                    <h3>
                                                                                        <a
                                                                                            href="{{ route('comic.show', ['slug' => $comic->slug]) }}">{{ $comic->title }}</a>
                                                                                    </h3>
                                                                                </div>
                                                                                @if ($comic->latestChapter)
                                                                                    <div class="chapter font-meta">
                                                                                        <span>Chương mới:
                                                                                            <a
                                                                                                href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $comic->latestChapter->slug]) }}">
                                                                                                {{ $comic->latestChapter->title }}
                                                                                            </a>
                                                                                        </span>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="post-on">
                                                                            {{ optional($comic->latestChapter)->updated_at ? $comic->latestChapter->updated_at->format('d/m/Y') : '---' }}
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="action">
                                                                            <a class="wp-manga-delete-bookmark text-danger"
                                                                                href="javascript:void(0)"
                                                                                title="Bỏ theo dõi">
                                                                                <i class="icon ion-ios-close"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="tab-pane" id="history">
                                                    <div class="tab-group-item">
                                                        <div class="tab-item tab-item-historyx">
                                                            <div class="row">
                                                                @foreach ($histories as $comic)
                                                                    <div class="col-md-4">
                                                                        <div class="history-content">
                                                                            <div class="item-thumb">
                                                                                @php
                                                                                    $imageSrc =
                                                                                        empty($comic->image) ||
                                                                                        $comic->image === 'noimage.png'
                                                                                            ? $comic->url_image
                                                                                            : asset(
                                                                                                'storage/images/covers/' .
                                                                                                    $comic->image,
                                                                                            );
                                                                                @endphp

                                                                                <a href="{{ route('comic.show', $comic->slug) }}"
                                                                                    title="{{ $comic->title }}">
                                                                                    <img class="img-responsive" width="100" height="60"
                                                                                        src="{{ $imageSrc }}"
                                                                                        onerror="this.onerror=null;this.src='{{ url('assets/img/default.jpg') }}';"
                                                                                        alt="{{ $comic->title }}">
                                                                                </a>

                                                                            </div>
                                                                            <div class="item-infor">
                                                                                <div class="settings-title">
                                                                                    <h3 class="line-2 font-16">
                                                                                        <a
                                                                                            href="{{ route('comic.show', ['slug' => $comic->slug]) }}">{{ $comic->title }}</a>
                                                                                    </h3>
                                                                                </div>
                                                                                @if ($comic->latestChapter)
                                                                                    <div class="chapter">
                                                                                        <span class="chap">
                                                                                            <a href="{{ route('chapter.show', ['comicSlug' => $comic->slug, 'chapterSlug' => $comic->latestChapter->slug]) }}"
                                                                                                class="btn-link">
                                                                                                {{ $comic->latestChapter->title }}
                                                                                            </a>
                                                                                        </span>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                            <div class="action">
                                                                                <span class="view pull-right">
                                                                                    <a class="visited-remove"
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

                                                <div class="tab-pane fade show active" id="account">
                                                    @if (session('success'))
                                                        <div class="alert alert-success">{{ session('success') }}</div>
                                                    @endif

                                                    @if ($errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul class="mb-0">
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif

                                                    <form method="POST" id="form-account-settings"
                                                        action="{{ route('user.setting') }}">
                                                        @csrf
                                                        <div class="tab-group-item">

                                                            {{-- Tên hiển thị --}}
                                                            <div class="tab-item">
                                                                <div class="settings-title">
                                                                    <h3>Thay đổi tên hiển thị</h3>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-md-3">Tên hiện tại</label>
                                                                    <div class="col-md-9">
                                                                        <span class="show">{{ $user->name }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-md-3">Tên mới</label>
                                                                    <div class="col-md-9">
                                                                        <input class="form-control" type="text"
                                                                            name="name"
                                                                            value="{{ old('name', $user->name) }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- Email --}}
                                                            <div class="tab-item">
                                                                <div class="settings-title">
                                                                    <h3>Thay đổi địa chỉ email</h3>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-md-3">Email hiện tại</label>
                                                                    <div class="col-md-9">
                                                                        <span class="show">{{ $user->email }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-md-3">Email mới</label>
                                                                    <div class="col-md-9">
                                                                        <input class="form-control" type="email"
                                                                            name="email"
                                                                            value="{{ old('email', $user->email) }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- Đổi mật khẩu --}}
                                                            <div class="tab-item" style="    border-bottom: none;">
                                                                <div class="settings-title">
                                                                    <h3>Thay đổi mật khẩu</h3>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-md-3">Mật khẩu hiện tại</label>
                                                                    <div class="col-md-9">
                                                                        <input class="form-control" type="password"
                                                                            name="current_password"
                                                                            placeholder="Mật khẩu cũ">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-md-3">Mật khẩu mới</label>
                                                                    <div class="col-md-9">
                                                                        <input class="form-control" type="password"
                                                                            name="password" placeholder="Mật khẩu mới">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-md-3">Xác nhận mật khẩu</label>
                                                                    <div class="col-md-9">
                                                                        <input class="form-control" type="password"
                                                                            name="password_confirmation"
                                                                            placeholder="Nhập lại mật khẩu">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- Submit --}}
                                                            <div class="form-group row">
                                                                <div class="col-md-3"></div>
                                                                <div class="col-md-9">
                                                                    <input class="btn btn-primary" type="submit"
                                                                        value="Cập nhật">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
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
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).on('click', '.visited-remove', function (e) {
        e.preventDefault();

        let btn = $(this);
        let comicId = btn.data('id');
        $.ajax({
            url: '/history/' + comicId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                btn.closest('.col-md-4').remove();
            },
            error: function (xhr) {
                alert('Lỗi: ' + xhr.responseJSON.message || 'Không thể xoá.');
            }
        });
    });
</script>

@endsection
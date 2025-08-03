@extends('site.master')

@section('head')
    <title>Tổng hợp tất cả các loại truyện - Mehentai</title>
    <meta name="description" content="Tổng hợp tất cả các loại truyện - Mehentai">
    <meta name="robots" content="index, follow">
    <meta name="googlebot-news" content="index,follow">
    <meta itemprop="name" content="Tổng hợp tất cả các loại truyện - Mehentai">
    <meta itemprop="description" content="Tổng hợp tất cả các loại truyện - Mehentai">

    <meta itemprop="image" content="{{ sourceSetting($settings['logo']) }}">
    <!--Meta Facebook Page Other-->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:title" content="Tổng hợp tất cả các loại truyện - Mehentai">
    <meta property="og:description" content="Tổng hợp tất cả các loại truyện - Mehentai">

    <meta content={{ sourceSetting($settings['logo']) }} property="og:image">
    <meta content={{ sourceSetting($settings['logo']) }} name="twitter:image">

    <meta property="og:url" content="{{ url('/') }}">
    <link rel="canonical" href="{{ url('/') }}">
    <link rel="next" href="{{ url('/') }}?page=2">
    <link rel="prev" href="">
@endsection

@section('main')
    <div class="site-content">
        <div class="c-breadcrumb-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <ol class="breadcrumb">
                            <li>
                                <a href="{{url('/')}}">
                                    Trang Chủ </a>
                            </li>
                            <li>
                                <a href="/genres">Thể Loại</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @if (isset($categories) && count($categories) > 0)
            <div class="page-category">
                <div class="container">
                    <ul class="list-unstyled row m-0 page-genres">
                        @foreach ($categories as $category)
                            <li class="col-6 col-md-2">
                                <a href="{{ route('site.category', ['categorySlug' => $category->slug]) }}">
                                    <i class="fa fa-caret-right"></i> {{ $category->name }}
                                    <span class="number-story"> {{ $category->comics_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

    </div>
@endsection

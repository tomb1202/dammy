@extends('admin.layouts.master')

@section('title')
    <title>Dashboard</title>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tổng thành viên</span>
                        <span class="info-box-number">{{ $totalUsers ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tổng truyện tranh</span>
                        <span class="info-box-number">{{ $totalComics ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tổng chương</span>
                        <span class="info-box-number">{{ $totalChapters ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-commenting"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tổng bình luận</span>
                        <span class="info-box-number">{{ $totalComments ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-user-plus"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Thành viên mới hôm nay</span>
                        <span class="info-box-number">{{ $todayUsers ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-plus-square"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Truyện mới hôm nay</span>
                        <span class="info-box-number">{{ $todayComics ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-plus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Chương mới hôm nay</span>
                        <span class="info-box-number">{{ $todayChapters ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($latestUsers) && count($latestUsers))
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thành viên đăng ký hôm nay</h3>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="users-list clearfix">
                            @php
                                $avatars = [
                                    'system/dist/img/user1-128x128.jpg',
                                    'system/dist/img/user5-128x128.jpg',
                                    'system/dist/img/user4-128x128.jpg',
                                    'system/dist/img/user6-128x128.jpg',
                                    'system/dist/img/user3-128x128.jpg',
                                    'system/dist/img/user1-128x128.jpg',
                                ];
                            @endphp
                            @foreach($latestUsers as $user)
                                @php
                                    $fallbackAvatar = url($avatars[array_rand($avatars)]);
                                    $imgSrc = $fallbackAvatar;
                                @endphp
                                <li>
                                    <img src="{{ $imgSrc }}" alt="User Image">
                                    <a class="users-list-name" href="#">{{ $user->name }}</a>
                                    <span class="users-list-date">{{ $user->created_at->format('d/m H:i') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>
@endsection

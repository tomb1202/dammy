@extends('admin.layouts.master')

@section('title')
    <title>Admin | Truyện tranh | Form</title>
@endsection

<style>
    ul.right-button li {
        display: inline-block;
    }
</style>

@section('content')
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-home"></i>Admin</a></li>
            <li><a href="{{ route('admin.comic.index') }}">Truyện tranh</a></li>
        </ol>
        <div class="clearfix"></div>
    </section>

    <section class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-home" data-toggle="tab"><span class="text-bold">Thông tin truyện</span></a>
                </li>

                @if (isset($comic))
                    <li><a href="#tab-chapters" data-toggle="tab"><span class="text-bold">Danh sách Chapter</span></a></li>
                @endif
            </ul>

            <form id="form-add" class="tab-content" action="{{ route('admin.comic.store') }}" method="post"
                enctype="multipart/form-data">
                @csrf

                {{-- Tab Thông tin --}}
                @include('admin.comic.tabs.info')

                @if (isset($comic))
                    @include('admin.comic.tabs.chapter')
                @endif


                {{-- Hidden ID nếu đang edit --}}
                @if (isset($comic))
                    <input type="hidden" name="id" value="{{ $comic->id }}">
                @endif

                <div class="box-footer">
                    <a href="{{ route('admin.comic.index') }}" class="btn btn-danger">Huỷ bỏ</a>
                    <button type="submit" name="action" value="save_add" class="btn btn-success">Cập nhật</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('script')
    @include('admin.comic.script')
@endsection

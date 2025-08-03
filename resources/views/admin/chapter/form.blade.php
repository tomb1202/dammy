@extends('admin.layouts.master')

@section('title')
    <title>Admin | Chương truyện | Form</title>
@endsection

@section('content')
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-home"></i>Admin</a></li>
            <li class=""><a href="{{ route('admin.chapter.index') }}">Chương truyện</a></li>
        </ol>
        <div class="clearfix"></div>
    </section>
    

    <section class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-home" data-toggle="tab"><span class="text-bold">Thông tin</span></a></li>
                <li><a href="#tab-pages" data-toggle="tab"><span class="text-bold">Trang truyện</span></a></li>
            </ul>

            <form id="form-add" class="tab-content" action="{{ route('admin.chapter.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                @include('admin.chapter.tabs.info')
                @include('admin.chapter.tabs.page')

                @if (isset($chapter))
                    <input type="hidden" name="id" id="id" value="{{ $chapter->id }}">
                @endif

                <div class="box-footer">
                    <a href="{{ route('admin.chapter.index') }}" class="btn btn-danger">Huỷ bỏ</a>
                    <button type="submit" name="action" value="save_add" class="btn btn-success">Cập nhật</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('script')
    @include('admin.chapter.script')
@endsection
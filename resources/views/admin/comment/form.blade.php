@extends('admin.layouts.master')

@section('title')
    <title>Admin | Bình luận | Form</title>
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
            <li class=""><a href="{{ route('admin.comment.index') }}">Bình luận</a></li>
        </ol>
        <div class="clearfix"></div>
    </section>

    <section class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-home" data-toggle="tab"><span class="text-bold">Thông tin</span></a></li>
            </ul>

            <form id="form-add" class="tab-content" action="{{ route('admin.comment.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                @include('admin.comment.tabs.info')

                @if (isset($comment))
                    <input type="hidden" name="id" value="{{ $comment->id }}">
                @endif

                <div class="box-footer">
                    <a href="{{ route('admin.comment.index') }}" class="btn btn-danger">Huỷ bỏ</a>
                    <button type="submit" name="action" value="save_add" class="btn btn-success">Cập nhật</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('script')
    @include('admin.comment.script')
@endsection

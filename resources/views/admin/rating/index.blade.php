@extends('admin.layouts.master')

@section('title')
    <title>Admin | Đánh giá</title>
@endsection

<style>
    tr.active { background: skyblue; }
    .dataTables_filter { float: right; }
    .buttons-excel {
        color: white;
        font-size: 12px;
        padding: 4px 10px;
    }
    div.dataTables_wrapper { width: 100%; margin: 0 auto; }
    th, td { white-space: nowrap; }
</style>

@section('content')
<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="/admin"><i class="fa fa-home"></i>Admin</a></li>
        <li class="active">Đánh giá</li>
    </ol>
    <div class="clearfix"></div>
</section>

<section class="content">
    <div class="box box-solid">
        <div class="box-body">
            <div class="search mb-3">
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-sm-6">
                            <input name="search" value="{{ request('search') }}" class="form-control" placeholder="Tìm theo truyện hoặc người dùng...">
                        </div>
                        <div class="col-sm-1 text-right">
                            <button class="btn btn-success" type="submit">Tìm</button>
                        </div>
                    </div>
                </form>
            </div>

            <table class="table table-bordered table-striped" id="example2" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Người dùng</th>
                        <th>Truyện</th>
                        <th>Điểm đánh giá</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $item)
                        <tr>
                            <td>{{ $data->firstItem() + $index }}</td>
                            <td>{{ $item->user->name ?? '---' }}</td>
                            <td>{{ $item->comic->title ?? '---' }}</td>
                            <td>{{ $item->rating }}/5</td>
                            <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {!! $data->appends(request()->input())->links('admin.widgets.default') !!}
        </div>
    </div>
</section>
@endsection

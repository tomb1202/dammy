@extends('admin.layouts.master')

@section('title')
    <title>Admin | Theo dõi</title>
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
        <li class="active">Theo dõi</li>
    </ol>
    <ul class="right-button">
        <li><button class="btn btn-danger btn-delete-multiple btn-sm" disabled>
            <i class="fa fa-trash"></i> Xóa mục đã chọn <span class="selected-count">(0)</span>
        </button></li>
    </ul>
    <div class="clearfix"></div>
</section>

<section class="content">
    <div class="box box-solid">
        <div class="box-body">
            <div class="search mb-3">
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-5">
                            <input name="search" value="{{ $request->search ?? '' }}" class="form-control" placeholder="Tìm theo tên người dùng hoặc truyện...">
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
                        <th><input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>Người dùng</th>
                        <th>Truyện</th>
                        <th>Thời gian</th>
                        <th><i class="fa fa-cogs"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $item)
                        <tr class="tr-{{ $item->id }}">
                            <td><input type="checkbox" class="select-item" value="{{ $item->id }}"></td>
                            <td>{{ $data->firstItem() + $index }}</td>
                            <td>{{ $item->user->name ?? '---' }}</td>
                            <td>{{ $item->comic->title ?? '---' }}</td>
                            <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '' }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $item->id }}"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {!! $data->appends(request()->input())->links('admin.widgets.default') !!}
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        if (!confirm('Bạn có chắc chắn muốn xóa không?')) return;

        $.ajax({
            url: "{{ route('admin.follow.delete') }}",
            type: "DELETE",
            data: { id, _token: "{{ csrf_token() }}" },
            success: function(res) {
                if (res.success) $('.tr-' + id).fadeOut();
                else alert('Xóa thất bại: ' + res.message);
            },
            error: function(xhr) {
                alert('Lỗi khi xóa: ' + xhr.responseJSON.message);
            }
        });
    });

    $(document).ready(function() {
        let selectedItems = [];

        $('#select-all').on('change', function() {
            $('.select-item').prop('checked', $(this).prop('checked')).trigger('change');
        });

        $('.select-item').on('change', function() {
            selectedItems = $('.select-item:checked').map(function() {
                return $(this).val();
            }).get();
            $('.selected-count').text(`(${selectedItems.length})`);
            $('.btn-delete-multiple').prop('disabled', selectedItems.length === 0);
        });

        $('.btn-delete-multiple').on('click', function() {
            if (!confirm(`Bạn có chắc muốn xóa ${selectedItems.length} mục không?`)) return;

            $.ajax({
                url: "{{ route('admin.follow.delete-multiple') }}",
                type: "DELETE",
                data: { ids: selectedItems, _token: "{{ csrf_token() }}" },
                success: function(res) {
                    if (res.success) {
                        selectedItems.forEach(id => $(`.tr-${id}`).fadeOut());
                        $('#select-all').prop('checked', false);
                        $('.selected-count').text('(0)');
                        $('.btn-delete-multiple').prop('disabled', true);
                    } else {
                        alert('Xóa thất bại!');
                    }
                },
                error: function() {
                    alert('Lỗi khi xóa dữ liệu!');
                }
            });
        });
    });
</script>
@endsection
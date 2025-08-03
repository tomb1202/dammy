@extends('admin.layouts.master')

@section('title')
    <title>Admin | Người dùng</title>

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
@endsection

@section('content')
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-home"></i>Admin</a></li>
            <li class="active">Người dùng</li>
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
                                <input name="search" value="{{ $request->search ?? '' }}" class="form-control" placeholder="Tìm kiếm theo tên hoặc email...">
                            </div>
                            <div class="col-sm-1 text-right">
                                <button class="btn btn-success" type="submit">Tìm kiếm</button>
                            </div>
                        </div>
                    </form>
                </div>

                <table class="table table-bordered table-striped" id="example2" style="width:100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>#</th>
                            <th>Ngày tham gia</th>
                            <th>Email</th>
                            <th>Tên</th>
                            <th>Hình ảnh</th>
                            <th>Trạng thái</th>
                            <th><i class="fa fa-cogs"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $item)
                            <tr class="tr-{{ $item->id }}">
                                <td><input type="checkbox" class="select-item" value="{{ $item->id }}"></td>
                                <td>{{ $data->firstItem() + $index }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    @if ($item->avatar)
                                        <img src="{{ $item->avatar }}" class="rounded-full" style="width:32px; height:32px; object-fit:cover; border-radius:9999px">
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td>{{ $item->is_active ? 'Hoạt động' : 'Đã khóa' }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm btn-view" data-id="{{ $item->id }}" title="Xem chi tiết"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('admin.user.edit', ['user' => $item->id]) }}" class="btn btn-primary btn-sm" title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
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

    {{-- Modal xem chi tiết --}}
    <div id="userDetailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Chi Tiết Người Dùng</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td id="modal_user_id"></td>
                        </tr>
                        <tr>
                            <th>Tên</th>
                            <td id="modal_user_username"></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td id="modal_user_email"></td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td id="modal_user_status"></td>
                        </tr>
                        <tr>
                            <th>Ngày tham gia</th>
                            <td id="modal_user_joined_at"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            if (!confirm('Bạn có chắc chắn muốn xóa không?')) return;

            $.ajax({
                url: "{{ route('admin.user.delete') }}",
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

        $(document).on('click', '.btn-view', function() {
            let id = $(this).data('id');

            $.ajax({
                url: `/admin/users/view/${id}`,
                type: "GET",
                success: function(res) {
                    if (res.success) {
                        const u = res.user;
                        $('#modal_user_id').text(u.id);
                        $('#modal_user_username').text(u.name);
                        $('#modal_user_email').text(u.email);
                        $('#modal_user_status').text(u.is_active ? 'Hoạt động' : 'Đã khóa');
                        $('#modal_user_joined_at').text(u.created_at);
                        $('#userDetailModal').modal('show');
                    } else {
                        alert('Không tìm thấy dữ liệu người dùng!');
                    }
                },
                error: function() {
                    alert('Lỗi khi tải dữ liệu!');
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
                if (!confirm(`Bạn có chắc chắn muốn xóa ${selectedItems.length} mục không?`)) return;

                $.ajax({
                    url: "{{ route('admin.user.delete-multiple') }}",
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

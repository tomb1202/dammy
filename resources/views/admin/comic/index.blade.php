@extends('admin.layouts.master')

@section('title')
    <title>Admin | Truyện tranh</title>

    <style>
        tr.active {
            background: skyblue;
        }

        .dataTables_filter {
            float: right;
        }

        .buttons-excel {
            color: white;
            font-size: 12px;
            padding: 4px 10px;
        }

        div.dataTables_wrapper {
            width: 100%;
            margin: 0 auto;
        }

        th,
        td {
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-home"></i>Admin</a></li>
            <li class="active">Truyện tranh</li>
        </ol>
        <ul class="right-button">
            <li><a href="{{ route('admin.comic.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Thêm
                    mới</a></li>
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
                            <div class="col-sm-3">
                                <select name="category_id" class="form-control">
                                    <option value="">-- Chọn chuyên mục --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $request->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="status" class="form-control">
                                    <option value="">-- Trạng thái --</option>
                                    <option value="ongoing" {{ $request->status == 'ongoing' ? 'selected' : '' }}>Đang ra
                                    </option>
                                    <option value="complete" {{ $request->status == 'complete' ? 'selected' : '' }}>Hoàn
                                        thành</option>
                                </select>
                            </div>

                            <div class="col-sm-5">
                                <input name="search" value="{{ $request->search ?? '' }}" class="form-control"
                                    placeholder="Tìm theo tiêu đề hoặc slug...">
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
                            <th>Ảnh bìa</th>
                            <th>Tiêu đề</th>
                            <th>Chuyên mục</th>
                            <th>Số chương</th>
                            <th>Tác giả</th>
                            <th>Trạng thái</th>
                            <th><i class="fa fa-cogs"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $item)
                            <tr class="tr-{{ $item->id }}">
                                <td><input type="checkbox" class="select-item" value="{{ $item->id }}"></td>
                                <td>{{ $data->firstItem() + $index }}</td>
                                <td>
                                    @if ($item->image)
                                        <a target="_blank" href="{{ asset('storage/images/covers/' . $item->image) }}">
                                            <img src="{{ asset('storage/images/covers/' . $item->image) }}"
                                                style="width: 40px; height: 60px; object-fit: cover;">
                                        </a>
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td>
                                    <a target="_blank" href="{{ route('comic.show', ['slug' => $item->slug]) }}">
                                        {!! Str::words($item->title, 10, '....') !!}
                                    </a>
                                </td>
                                <td>
                                    @if ($item->categories && $item->categories->count())
                                        @foreach ($item->categories->take(5) as $category)
                                            <a target="_blank"
                                                href="{{ route('site.category', ['categorySlug' => $category->slug]) }}">
                                                {{ $category->name }}
                                            </a>{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    @else
                                        ---
                                    @endif
                                </td>

                                <td>
                                    <span class="btn btn-sm bg-info">{{ $item->chapters_count ?? 0 }}</span>
                                </td>
                                <td>{{ $item->author ?? '---' }}</td>
                                <td>
                                    @if ($item->status == 'ongoing')
                                        <span class="btn btn-sm bg-warning">Đang ra</span>
                                    @else
                                        <span class="btn btn-sm bg-info">Hoàn tất</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.comic.edit', ['comic' => $item->id]) }}"
                                        class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $item->id }}"><i
                                            class="fa fa-times"></i></button>
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
                url: "{{ route('admin.comic.delete') }}",
                type: "DELETE",
                data: {
                    id,
                    _token: "{{ csrf_token() }}"
                },
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
                    url: "{{ route('admin.comic.delete-multiple') }}",
                    type: "DELETE",
                    data: {
                        ids: selectedItems,
                        _token: "{{ csrf_token() }}"
                    },
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

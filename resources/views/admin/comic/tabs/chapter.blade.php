<div class="tab-pane" id="tab-chapters">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid">
                <div class="box-body">
                    <table class="table table-bordered table-striped" id="example2" style="width:100%">
                        <thead>
                            <tr>
                                <th>Chương</th>
                                <th>Slug</th>
                                <th>Lượt xem</th>
                                <th>Số trang</th>
                                <th><i class="fa fa-cogs"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comic->chapters as $index => $item)
                                <tr class="tr-{{ $item->id }}">
                                    <td>
                                        <strong><a
                                                href="{{ route('admin.comic.edit', ['comic' => $item->comic->id]) }}">{{ $item->comic->title ?? '---' }}</a></strong><br>
                                        <small>{{ $item->title }}</small>
                                    </td>
                                    <td>{{ $item->slug }}</td>
                                    <td>{{ $item->views }}</td>
                                    <td>{{ $item->pages_count ?? 0 }}</td>
                                    <td>
                                        <a href="{{ route('admin.chapter.edit', ['chapter' => $item->id]) }}"
                                            class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                        <button class="btn btn-danger btn-sm btn-delete"
                                            data-id="{{ $item->id }}"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

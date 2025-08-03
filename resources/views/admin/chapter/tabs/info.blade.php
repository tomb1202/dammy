<div class="tab-pane active" id="tab-home">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid">
                <div class="box-body">

                    {{-- Thông tin chương --}}
                    <div class="row">
                        {{-- Chọn truyện --}}
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Chọn truyện <span class="text-danger">*</span></label>
                                <select name="comic_id" id="comic_select" class="form-control select2"
                                        data-placeholder="Chọn truyện..." data-ajax-url="{{ route('admin.comic.search') }}" required>
                                    @if (!empty($chapter->comic))
                                        <option value="{{ $chapter->comic->id }}" selected>{{ $chapter->comic->title }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- Tiêu đề --}}
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tiêu đề chương <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control"
                                       value="{{ old('title', $chapter->title ?? '') }}" placeholder="VD: Chương 1" required>
                            </div>
                        </div>

                        {{-- Slug --}}
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Slug</label>
                                <input type="text" name="slug" class="form-control"
                                       value="{{ old('slug', $chapter->slug ?? '') }}" placeholder="Tự động tạo nếu để trống">
                            </div>
                        </div>

                        {{-- Số chương --}}
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Số chương</label>
                                <input type="number" name="number" class="form-control"
                                       value="{{ old('number', $chapter->number ?? '') }}" placeholder="VD: 1, 2, 3...">
                            </div>
                        </div>
                    </div>

                    {{-- Meta SEO --}}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Meta Title</label>
                                <input type="text" name="meta_title" class="form-control"
                                       value="{{ old('meta_title', $chapter->meta_title ?? '') }}">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Meta Keywords</label>
                                <input type="text" name="meta_keywords" class="form-control"
                                       value="{{ old('meta_keywords', $chapter->meta_keywords ?? '') }}">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="1">{{ old('meta_description', $chapter->meta_description ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

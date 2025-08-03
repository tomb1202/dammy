<div class="tab-pane active" id="tab-home">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid">
                <div class="box-body">
                    {{-- Tiêu đề + Slug --}}
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group clearfix">
                                <label class="control-label">Tiêu đề: <strong class="red">*</strong></label>
                                <input type="text" name="title" id="title" value="{{ old('title', isset($comic) ? $comic->title : '') }}"
                                       class="form-control" placeholder="Tên truyện" required>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group clearfix">
                                <label class="control-label">Slug:</label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug', isset($comic) ? $comic->slug : '') }}"
                                       class="form-control" placeholder="slug-tu-dong">
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group clearfix">
                                <label class="control-label">Trạng thái:</label>
                                <select name="status" class="form-control">
                                    <option value="ongoing" {{ (old('status', isset($comic) ? $comic->status : '') == 'ongoing') ? 'selected' : '' }}>Đang cập nhật</option>
                                    <option value="complete" {{ (old('status', isset($comic) ? $comic->status : '') == 'complete') ? 'selected' : '' }}>Hoàn thành</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Danh mục --}}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group clearfix">
                                <label class="control-label">Danh mục: <strong class="red">*</strong></label>
                                <select name="category_ids[]" class="form-control select2" multiple required>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ (isset($comic) && $comic->categories->contains('id', $cat->id)) || (is_array(old('category_ids')) && in_array($cat->id, old('category_ids', []))) ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Mô tả truyện --}}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group clearfix">
                                <label class="control-label">Mô tả:</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Giới thiệu ngắn">{{ old('description', isset($comic) ? $comic->description : '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- SEO: Meta Title, Meta Desc, Meta Keywords --}}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group clearfix">
                                <label class="control-label">Meta Title:</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title', isset($comic) ? $comic->meta_title : '') }}"
                                       class="form-control" placeholder="Meta title cho SEO">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group clearfix">
                                <label class="control-label">Meta Keywords:</label>
                                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', isset($comic) ? $comic->meta_keywords : '') }}"
                                       class="form-control" placeholder="Từ khoá SEO">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group clearfix">
                                <label class="control-label">Meta Description:</label>
                                <textarea name="meta_description" class="form-control" rows="3" placeholder="Mô tả SEO">{{ old('meta_description', isset($comic) ? $comic->meta_description : '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Trạng thái + Ảnh bìa --}}
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Ảnh bìa:</label>
                                <input type="file" name="image" class="form-control">
                                @if (isset($comic) && !empty($comic->image))
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/images/covers/' . $comic->image) }}" style="width: 64px; height: 96px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

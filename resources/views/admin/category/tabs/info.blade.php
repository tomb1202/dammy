<div class="tab-pane active" id="tab-home">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        {{-- Tên thể loại --}}
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="control-label">Tên: <strong class="red">*</strong></label>
                                <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="form-control" placeholder="Tên thể loại" required>
                            </div>
                        </div>

                        {{-- Slug --}}
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="control-label">Slug:</label>
                                <input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}" class="form-control" placeholder="slug-tu-dong-hoac-tu-dien">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Meta Title --}}
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="control-label">Meta Title:</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title', $category->meta_title ?? '') }}" class="form-control" placeholder="Meta title cho SEO">
                            </div>
                        </div>

                        {{-- Meta Keywords --}}
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="control-label">Meta Keywords:</label>
                                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $category->meta_keywords ?? '') }}" class="form-control" placeholder="Từ khoá SEO">
                            </div>
                        </div>
                    </div>

                    {{-- Meta Description --}}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group clearfix">
                                <label class="control-label">Meta Description:</label>
                                <textarea name="meta_description" class="form-control" rows="3" placeholder="Mô tả SEO">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Sort & URL --}}
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="control-label">Thứ tự hiển thị:</label>
                                <input type="number" name="sort" value="{{ old('sort', $category->sort ?? '') }}" class="form-control" placeholder="Thứ tự ưu tiên">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="control-label">URL:</label>
                                <input type="text" name="url" value="{{ old('url', $category->url ?? '') }}" class="form-control" placeholder="Link chuyển hướng nếu có">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

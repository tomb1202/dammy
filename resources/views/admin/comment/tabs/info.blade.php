<div class="tab-pane active" id="tab-home">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid">
                <div class="box-body">
                    {{-- Thông tin chương và truyện --}}
                    @if (!empty($comment->chapter))
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group clearfix">
                                    <label class="control-label">Truyện:</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $comment->chapter->comic->title ?? '---' }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group clearfix">
                                    <label class="control-label">Chương:</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $comment->chapter->title ?? '---' }}">
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Người dùng --}}
                    @if (!empty($comment->user))
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group clearfix">
                                    <label class="control-label">Người dùng:</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $comment->user->name ?? '---' }}">
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Nội dung --}}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group clearfix">
                                <label class="control-label">Nội dung bình luận: <strong class="red">*</strong></label>
                                <textarea name="content" rows="4" class="form-control"
                                    required>{{ old('content', $comment->content ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    @if (!empty($comment))
                        <input type="hidden" name="id" value="{{ $comment->id }}">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tab-pane" id="tab-pages">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid">
                <div class="box-body">
                    <h4>Danh sách trang truyện</h4>

                    <div id="chapter-pages-wrapper">
                        @if (!empty($chapter->pages))

                            @foreach ($chapter->pages->sortBy('sort') as $index => $page)

                                    @php
                                        $imagePath = '';
                                        if (!empty($page->url_image)) {
                                            $imagePath = $page->url_image;
                                        } else {
                                            $imagePath = asset('assets/images/coming-soon.avif');
                                        }
                                    @endphp

                                <div class="chapter-page-item row align-items-center mb-3" data-id="{{ $page->id }}">
                                    <input type="hidden" name="pages[{{ $index }}][id]" value="{{ $page->id }}">

                                    <div class="col-md-2">
                                        <a href="{{ route('view-image', ['image' => $imagePath]) }}" target="_blank">
                                            <img src="{{ $imagePath }}"
                                                 class="img-thumbnail"
                                                 style="height: 80px; object-fit: cover;">
                                        </a>
                                    </div>
                                    
                                    <div class="col-md-5">
                                        <input type="file" name="pages[{{ $index }}][image]" class="form-control form-control-sm" accept="image/*">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="pages[{{ $index }}][sort]" value="{{ $page->sort }}" class="form-control form-control-sm" placeholder="STT">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-page"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Thêm trang mới</label>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-add-page"><i class="fa fa-plus"></i> Thêm trang</button>
                    </div>

                    <div id="new-pages-list"></div>
                </div>
            </div>
        </div>
    </div>
</div>

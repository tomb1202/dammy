<script>
    $(function() {
        // Auto-generate slug
        $('input[name="title"]').on('input', function() {
            let slugInput = $('input[name="slug"]');
            if (slugInput.val().length === 0) {
                slugInput.val($(this).val().toLowerCase()
                    .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // remove accents
                    .replace(/\s+/g, '-').replace(/[^a-z0-9\-]/g, '')
                );
            }
        });

        // Remove page item
        $(document).on('click', '.btn-remove-page', function() {
            $(this).closest('.chapter-page-item').remove();
        });

        // Select2 AJAX for comic
        $('#comic_select').select2({
            placeholder: $(this).data('placeholder'),
            ajax: {
                url: $('#comic_select').data('ajax-url'),
                dataType: 'json',
                delay: 250,
                data: params => ({
                    q: params.term
                }),
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.id,
                        text: item.title
                    }))
                }),
                cache: true
            },
            allowClear: true,
            minimumInputLength: 1,
            width: '100%'
        });


        let newPageIndex = 0;

        $('#btn-add-page').on('click', function() {
            let html = `
        <div class="chapter-page-item row align-items-center mb-2">
            <div class="col-md-5">
                <input type="file" name="new_pages[${newPageIndex}][image]" class="form-control form-control-sm" accept="image/*">
            </div>
            <div class="col-md-2">
                <input type="number" name="new_pages[${newPageIndex}][sort]" class="form-control form-control-sm" placeholder="STT">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm btn-remove-page"><i class="fa fa-trash"></i></button>
            </div>
        </div>`;
            $('#new-pages-list').append(html);
            newPageIndex++;
        });

        $(document).on('click', '.btn-remove-page', function() {
            $(this).closest('.chapter-page-item').remove();
        });

    });
</script>

@section('script')
<script>
    // Gợi ý slug từ tiêu đề
    $('#title').on('input', function () {
        const slug = $(this).val().toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
        $('#slug').val(slug);
    });

    // Init Select2
    $('.select2').select2({
        placeholder: 'Chọn danh mục',
        allowClear: true,
        width: '100%',
    });
</script>
@endsection

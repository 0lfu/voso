$(document).ready(function () {
    $('#desc-area').on('input', function () {
        const charCount = $(this).val().length;
        $('#charCount').text(charCount);
    });
    $('#desc-area').trigger('input');
});
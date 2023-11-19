$(document).ready(function () {
    $('.series-row').click(function () {
        $(this).next('.episodes-container').toggle();
    });
});
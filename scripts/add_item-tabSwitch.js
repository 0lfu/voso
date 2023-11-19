$(document).ready(function() {
    $('.add-series-button').click(function() {
        if (!$(this).hasClass('active')) {
            $('.add-series-form').toggleClass('active').css('display', 'block');
            $('.add-episode-form').removeClass('active').css('display', 'none');
            $('.add-many-form').removeClass('active').css('display', 'none');
            $('.add-series-button').toggleClass('active');
            $('.add-episode-button').removeClass('active');
            $('.add-many-button').removeClass('active');
            setSelectSize();
        }
    });

    $('.add-episode-button').click(function() {
        if (!$(this).hasClass('active')) {
            $('.add-series-form').removeClass('active').css('display', 'none');
            $('.add-many-form').removeClass('active').css('display', 'none');
            $('.add-episode-form').toggleClass('active').css('display', 'block');
            $('.add-many-button').removeClass('active');
            $('.add-series-button').removeClass('active');
            $('.add-episode-button').toggleClass('active');
            setSelectSize()
        }
    });
    $('.add-many-button').click(function() {
        if (!$(this).hasClass('active')) {
            $('.add-series-form').removeClass('active').css('display', 'none');
            $('.add-episode-form').removeClass('active').css('display', 'none');
            $('.add-many-form').toggleClass('active').css('display', 'block');
            $('.add-series-button').removeClass('active');
            $('.add-episode-button').removeClass('active');
            $('.add-many-button').toggleClass('active');
        }
    });
});
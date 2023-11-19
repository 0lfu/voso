$(document).ready(function () {
    $('.dropdown-header').click(function () {
        $('.dropdown-list').toggle();
        $('.custom-dropdown').toggleClass('active');
    });
    $(document).click(function (e) {
        var target = e.target;
        if (!$(target).is('.custom-dropdown') && !$(target).parents().is('.custom-dropdown')) {
            $('.dropdown-list').hide();
            $('.custom-dropdown').removeClass('active');
        }
    });

    function updateSelectedSource(selectedOption) {
        $('.dropdown-list li').removeClass('selected');
        selectedOption.addClass('selected');

        $('.selected-marker').remove();
        selectedOption.prepend('<span class="selected-marker">●</span>');

        var videoUrl;
        if (selectedOption.data('url')) {
            videoUrl = selectedOption.data('url');
        } else if (selectedOption.data('url1080p')) {
            const videoUrl1080p = selectedOption.data('url1080p');
            const videoUrl720p = selectedOption.data('url720p');
            const videoUrl480p = selectedOption.data('url480p');
            const videoUrl360p = selectedOption.data('url360p');

            videoUrl = videoUrl1080p || videoUrl720p || videoUrl480p || videoUrl360p;
        }

        player.src(videoUrl);
        player.play();
    }

    if (player.currentSrc() && player.currentSrc() !== "") {
        var defaultOption = $('.dropdown-list li[data-url="' + player.currentSrc() + '"]');

        if (defaultOption.length > 0) {
            $('.dropdown-header .sourceText').text(defaultOption.text());
            updateSelectedSource(defaultOption);
        } else {
            var firstOption = $('.dropdown-list li').first();
            $('.dropdown-header .sourceText').text(firstOption.text());
            updateSelectedSource(firstOption);
        }
    }

    $('.dropdown-list li').click(function () {
        updateSelectedSource($(this));
    });
});

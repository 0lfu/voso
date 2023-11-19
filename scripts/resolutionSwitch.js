$(document).ready(function () {
    $('.dropdown-list li').click(function () {
        var selectedOption = $(this);
        const videoUrl = selectedOption.data('url');
        const isCdaVideo = selectedOption.data('url1080p') !== undefined;
        if (isCdaVideo) {
            const videoUrl1080p = selectedOption.data('url1080p');
            const videoUrl720p = selectedOption.data('url720p');
            const videoUrl480p = selectedOption.data('url480p');
            const videoUrl360p = selectedOption.data('url360p');
            player.updateSrc([
                {
                    src: videoUrl1080p,
                    type: 'video/mp4',
                    label: '1080p'
                },
                {
                    src: videoUrl720p,
                    type: 'video/mp4',
                    label: '720p'
                },
                {
                    src: videoUrl480p,
                    type: 'video/mp4',
                    label: '480p'
                },
                {
                    src: videoUrl360p,
                    type: 'video/mp4',
                    label: '360p'
                }
            ])
        } else {
            player.updateSrc([
                {
                    src: videoUrl,
                    type: 'video/mp4',
                    label: 'HD'
                },
            ])
        }
        player.load();
        player.play();
        $('.dropdown-header .sourceText').text(selectedOption.text());
        $('.dropdown-list').hide();
        $('.custom-dropdown').removeClass('active');
    });
});

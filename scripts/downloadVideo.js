$(document).ready(function () {
    // download handler
    $('.download').click(function () {
        var downloadLink = $('<a>').attr({
            href: player.currentSrc(),
            download: episodeTitle,
            target: "_blank"
        });
        downloadLink[0].click();
    });
});
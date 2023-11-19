$(document).ready(function () {
    var playerContainer = document.getElementById('o-video');
    playerContainer.addEventListener('wheel', function (event) {
        if (event.target === playerContainer) {
            event.preventDefault();
        }
    }, {passive: false});

    var controlBar = document.querySelector('.video-js .vjs-control-bar');
    var controlBarChildren = controlBar.children;

    for (var i = 0; i < controlBarChildren.length; i++) {
        controlBarChildren[i].addEventListener('click', function () {
            this.blur();
            player.focus();
        });
    }
});

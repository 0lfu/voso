$(document).ready(function () {
    if (!isNaN(startIntroTime) && !isNaN(endIntroTime) && Number(startIntroTime) > 0 && Number(endIntroTime) > 0) {
        var Button = videojs.getComponent('Button');

        class CloseButton extends Button {
            constructor(player, options) {
                super(player, options);
                this.controlText("skip intro");
                this.addClass('vjs-skipintro-button');
                this.hide();
            }

            handleClick() {
                player.currentTime(endIntroTime);
                player.focus();
                player.play();
            }
        }

        videojs.registerComponent('closeButton', CloseButton);
        var customButton = player.addChild('closeButton', {});

        player.on('timeupdate', function () {
            var currentTime = player.currentTime();
            if (currentTime >= startIntroTime && currentTime < endIntroTime) {
                customButton.show();
            } else {
                customButton.hide();
            }
        });
    }
});

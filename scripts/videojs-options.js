const options = {
    techOrder: ['chromecast', 'html5'],
    controlBar: {
        remainingTimeDisplay: false,
        currentTimeDisplay: true
    },
    plugins: {
        videoJsResolutionSwitcher: {
            default: 'high',
            dynamicLabel: true
        },
        hotkeys: {
            volumeStep: 0.1,
            seekStep: 5,
            enableModifiersForNumbers: false,
            customKeys: {
                skip: {
                    key: function (event) {
                        return (event.which === 80);
                    },
                    handler: function (player, options, event) {
                        if (!isNaN(endIntroTime) && Number(endIntroTime) > 0 && player.currentTime() < endIntroTime) {
                            player.currentTime(endIntroTime);
                        }
                    }
                },
            }
        }
    },
};
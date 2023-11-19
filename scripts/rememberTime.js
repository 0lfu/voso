(function (window, videojs) {
    "use strict";

    const plugin = function (options) {
        var player = this,
            isLoaded;
        if (!options) options = {};

        var videoID = getVideoIDFromURL(window.location.href);

        player.on("timeupdate", time_updated);
        player.on("ended", time_updated);

        function time_updated(time_update_event) {
            var current_time = this.currentTime();
            var duration = this.duration();
            var time = Math.floor(current_time);

            if (time > duration || time_update_event.type === "ended") {
                time = 0;
            }

            if (isLoaded) {
                if (options.localStorageKey) {
                    localStorage[options.localStorageKey + "_" + videoID] = time;
                }

                if (options.sessionStorageKey) {
                    sessionStorage[options.sessionStorageKey + "_" + videoID] = time;
                }
            }
        }

        player.ready(function () {
            var seekFunction = function () {
                if (isLoaded) return;
                isLoaded = true;
                var seek;

                if (options.localStorageKey) {
                    seek = parseInt(
                        localStorage[options.localStorageKey + "_" + videoID]
                    );
                }

                if (options.sessionStorageKey) {
                    seek = parseInt(
                        sessionStorage[options.sessionStorageKey + "_" + videoID]
                    );
                }

                player.currentTime(seek);
            };

            player.one("playing", seekFunction);
            player.one("play", seekFunction);
            player.one("loadedmetadata", seekFunction);
        });

        window.addEventListener("message", function (evt) {
            try {
                if (typeof evt.data === "string") {
                    var seek;

                    if (evt.data.slice(0, 16) === "localStorageKey:") {
                        options.localStorageKey = evt.data.slice(16) + "_" + videoID;
                        seek = parseInt(localStorage[options.localStorageKey]);
                    }

                    if (evt.data.slice(0, 18) === "sessionStorageKey:") {
                        options.sessionStorageKey = evt.data.slice(18) + "_" + videoID;
                        seek = parseInt(sessionStorage[options.sessionStorageKey]);
                    }

                    if (seek && isLoaded) {
                        player.currentTime(seek);
                    }
                }
            } catch (error) {
                console.error("Error processing the message:", error);
            }
        });
    };

    function getVideoIDFromURL(url) {
        const urlParams = new URLSearchParams(new URL(url).search);
        return urlParams.get("v");
    }

    videojs.registerPlugin("remember", plugin);
})(window, window.videojs);

function setSelectSize() {
    if ($('.add-series-button').hasClass('active')) {
        var height = $("input#form-seriesId").outerHeight(true);
        $("input[type='date']").css('height', height);
        $('.brdtype select').css('height', height);
        $('.multiselect-dropdown').css('height', height);
    }
    if ($('.add-episode-button').hasClass('active')) {
        var height = $("input#form-episodeId").outerHeight(true);
        $("input[name='visible']").css('height', height);
        $('.series select').css('height', height);
    }
    if ($('.edit-series-form').length) {
        var height = $("input#form-seriesId").outerHeight(true);
        $("input[type='date']").css('height', height);
        $('.brdtype select').css('height', height);
        $('.multiselect-dropdown').css('height', height);
    }
    if ($('.edit-episode-form').length) {
        var height = $("input#form-episodeId").outerHeight(true);
        $("input[name='visible']").css('height', height);
        $('.series select').css('height', height);
    }
}

$(document).ready(function () {
    setSelectSize();

    function observeDOMChanges(callback) {
        const observer = new MutationObserver(callback);
        observer.observe(document.body, {subtree: true, childList: true});
    }

    observeDOMChanges(function (mutationsList, observer) {
        setSelectSize();
    });
});
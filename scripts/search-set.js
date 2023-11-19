$(document).ready(function () {
    function getURLParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    const searchValue = getURLParameter('q');
    const typeValue = getURLParameter('type');
    const genreValue = getURLParameter('genre');
    const sortValue = getURLParameter('sort');

    $(".search").val(searchValue);

    if (typeValue) {
        $("#type").val(typeValue);
    }
    if (genreValue) {
        $("#filter-genre-list").val(genreValue);
    }

    if (sortValue) {
        $("#sort").val(sortValue);
    }
});
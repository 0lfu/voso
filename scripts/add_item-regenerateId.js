$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "scripts/generate_uid",
        success: function (data) {
            $("#form-seriesId").val(data);
        }
    });
    $.ajax({
        type: "GET",
        url: "scripts/generate_uid",
        success: function (data) {
            $("#form-episodeId").val(data);
        }
    });
    $(".regenerate-seriesID-button").click(function () {
        $.ajax({
            type: "GET",
            url: "scripts/generate_uid",
            success: function (data) {
                $("#form-seriesId").val(data);
            }
        });
    });
    $(".regenerate-episodeID-button").click(function () {
        $.ajax({
            type: "GET",
            url: "scripts/generate_uid",
            success: function (data) {
                $("#form-episodeId").val(data);
            }
        });
    });
});
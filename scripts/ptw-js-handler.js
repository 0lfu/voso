$(document).ready(function () {
    $('.ptw-button').click(function () {
        const series_id = $(this).data('series-id');
        $.ajax({
            url: 'scripts/ptw-handler',
            type: 'post',
            data: {series_id: series_id},
            success: function (response) {
                var response = JSON.parse(response);
                if (response.status == "added") {
                    $('.ptw-button').removeClass('fa-plus').addClass('fa-bookmark');
                    $('.ptw-button').removeClass('series-toadd').addClass('series-added');
                } else if (response.status == "removed") {
                    $('.ptw-button').removeClass('fa-bookmark').addClass('fa-plus');
                    $('.ptw-button').removeClass('series-added').addClass('series-toadd');
                } else {
                    console.log("error");
                }
            }
        });
    });
});
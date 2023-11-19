$(document).ready(function() {
    $('.like-button').click(function() {
        const video_id = $(this).data('video-id');
        $.ajax({
            url: 'scripts/like-handler',
            type: 'post',
            data: {video_id: video_id},
            success: function(response) {
                var response = JSON.parse(response);
                if(response.status == "liked"){
                    $('.like-button').removeClass('far').addClass('fas');
                    $('.video-interactive p')[0].innerHTML = parseInt($('.video-interactive p').first().text())+1;
                }else if(response.status == "unliked"){
                    $('.like-button').removeClass('fas').addClass('far');
                    $('.video-interactive p')[0].innerHTML = parseInt($('.video-interactive p').first().text())-1;
                }
                else if(response.status == "notloggedin"){
                    const noty = new Noty({
                        text: 'Log in to like the video!',
                        type: 'error',
                        theme: 'relax',
                        layout: 'topRight',
                        timeout: 1500,
                    });
                    noty.show();
                }
                else{
                    console.log("error");
                }
            }
        });
    });
});
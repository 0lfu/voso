$(document).ready(function () {
    $("#report-form").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "scripts/report-comment-handle",
            data: $("#report-form").serialize(),
            success: function (response) {
                const data = JSON.parse(response);
                $('select.form-control option:first-child').prop('selected', true);
                $('#report-form textarea').val('');
                $('.form-popup-bg').removeClass('is-visible');
                if (data.status === "success") {
                    new Noty({
                        text: data.message,
                        type: 'success',
                        theme: 'relax',
                        layout: 'topRight',
                        timeout: 5000
                    }).show();
                } else {
                    new Noty({
                        text: data.message,
                        type: 'error',
                        theme: 'relax',
                        layout: 'topRight',
                        timeout: 5000
                    }).show();
                }
            }
        });
    });
});

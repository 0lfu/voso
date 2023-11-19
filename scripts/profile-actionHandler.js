$(document).ready(function () {
    document.querySelectorAll(".changepassword").forEach(element => {
        element.onclick = event => {
            event.preventDefault();
            $('.chngpass-form').addClass('is-visible');
        };

        $('.chngpass-form').on('click', function (event) {
            if ($(event.target).is('#btnCloseForm')) {
                event.preventDefault();
                $('#changepas-form :input').val('');
                $(this).removeClass('is-visible');
            }
        });
    });
    document.querySelectorAll(".removeaccount").forEach(element => {
        element.onclick = event => {
            event.preventDefault();
            $('.rmvacc-form').addClass('is-visible');
        };

        $('.rmvacc-form').on('click', function (event) {
            if ($(event.target).is('#btnCloseForm')) {
                event.preventDefault();
                $('#rmvacc-form :input').val('');
                $(this).removeClass('is-visible');
            }
        });
    });
});
$("#changepas-form").validate({
    rules: {
        new: {
            required: true,
            minlength: 6,
            maxlength: 20
        },
        renew: {
            required: true,
            equalTo: ".new"
        }
    },
    messages: {
        new: {
            required: "This field is required!",
            minlength: "Password should be at least 6 characters long!",
            maxlength: "Password cannot be more than 99 characters long!"
        },
        renew: {
            required: "This field is required!",
            equalTo: "Passwords do not match!"
        }
    },
    submitHandler: function (form) {
        form.submit();
    }
});
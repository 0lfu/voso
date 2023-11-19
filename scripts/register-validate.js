$("#form-register").validate({
    rules: {
      username: {
        required: true,
        minlength: 2,
        maxlength: 20
      },
      password: {
          required: true,
          minlength: 6,
          maxlength: 99
      },
      repassword: {
        required: true,
        equalTo: ".password"
    },
      email: {
        required: true,
        email: true
    }
    },
    messages: {
        username: {
            required: "This field is required!",
            minlength: "Username should be at least 2 characters long!",
            maxlength: "Username cannot be more than 20 characters long!"
        },
        password: {
            required: "This field is required!",
            minlength: "Password should be at least 6 characters long!",
            maxlength: "Password cannot be more than 99 characters long!"
        },
        repassword: {
            required: "This field is required!",
            equalTo: "Passwords do not match!"
        },
        email: {
            required: "This field is required!",
            email: "Enter a valid email address"
        }
    },
    submitHandler: function(form) {
      $(form).append('<input type="hidden" name="register_form_submit" value="1">');
      form.submit();
    }
  });
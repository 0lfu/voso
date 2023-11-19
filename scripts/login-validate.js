$("#form-login").validate({
    rules: {
      username: {
        required: true
      },
      password: {
          required: true
      }
    },
    messages: {
        username: {
            required: "This field is required!"
          },
          password: {
              required: "This field is required!"
          }
    },
    submitHandler: function(form) {
      $(form).append('<input type="hidden" name="login_form_submit" value="1">');
      form.submit();
    }
  });
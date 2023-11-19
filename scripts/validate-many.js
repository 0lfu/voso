function commonManySubmitHandler(form) {
    form.submit();
}

const commonManyValidationOptions = {
    rules: {
        series: {
            required: true
        },
        videolinks: {
            required: true
        },
        thumbnailinks: {
            required: true
        },
        titles: {
            required: true
        }
    },
    messages: {
        series: {
            required: "This field is required"
        },
        videolinks: {
            required: "This field is required"
        },
        thumbnailinks: {
            required: "This field is required"
        },
        titles: {
            required: "This field is required"
        }
    },
    submitHandler: commonManySubmitHandler
};

$("#form-addMany").validate(commonManyValidationOptions);
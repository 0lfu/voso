function commonEpisodeSubmitHandler(form) {
    form.submit();
}

const commonEpisodeValidationOptions = {
    rules: {
        id: {
            required: true,
            digits: true,
            step: 1,
            minlength: 9,
            maxlength: 9,
            min: 100000000,
            max: 999999999
        },
        series: {
            required: true
        },
        title: {
            required: true,
            minlength: 2
        },
        number: {
            required: true,
            min: 0,
            step: 1,
            digits: true
        },
        url: {
            required: true,
            url: true
        },
        poster: {
            required: true,
            url: true
        },
        desc: {
            maxlength: 2048
        }
    },
    messages: {
        id: {
            required: "This field is required",
            digits: "Incorrect ID format",
            step: "Incorrect ID format",
            minlength: "Incorrect ID format",
            maxlength: "Incorrect ID format",
            min: "Incorrect ID format",
            max: "Incorrect ID format"
        },
        series: {
            required: "This field is required"
        },
        title: {
            required: "This field is required",
            minlength: "Name is too short"
        },
        number: {
            required: "This field is required",
            number: "Enter a valid number",
            min: "Episode number must be a non-negative integer",
            step: "Episode number must be a non-negative integer",
            digits: "Episode number must be a non-negative integer"
        },
        url: {
            required: "This field is required",
            url: "Enter a valid URL"
        },
        poster: {
            required: "This field is required",
            url: "Enter a valid URL"
        },
        desc: {
            maxlength: "Description cannot exceed 2048 characters"
        }
    },
    submitHandler: commonEpisodeSubmitHandler
};

$("#form-addEpisode").validate(commonEpisodeValidationOptions);
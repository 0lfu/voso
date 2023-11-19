function commonSeriesSubmitHandler(form) {
    form.submit();
}

const commonSeriesValidationOptions = {
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
        altname: {
            required: true,
            minlength: 2
        },
        fullname: {
            required: true,
            minlength: 3
        },
        season: {
            required: true,
            min: 0,
            step: 1,
            digits: true
        },
        epcount: {
            required: true,
            min: 0,
            step: 1,
            digits: true
        },
        brdtype: {
            required: true,
        },
        brdstart: {
            required: true,
            date: true
        },
        desc: {
            required: true,
            maxlength: 1024
        },
        poster: {
            required: true,
            url: true
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
        altname: {
            required: "This field is required",
            minlength: "Name is too short"
        },
        fullname: {
            required: "This field is required",
            minlength: "Full name is too short"
        },
        season: {
            required: "This field is required",
            number: "Enter a valid number",
            min: "Season cannot be less than 0",
            step: "Season must be a non-negative integer",
            digits: "Season must be a non-negative integer"
        },
        epcount: {
            required: "This field is required",
            number: "Enter a valid number",
            min: "Number of episodes must be a non-negative integer",
            step: "Number of episodes must be a non-negative integer",
            digits: "Number of episodes must be a non-negative integer"
        },
        brdtype: {
            required: "This field is required"
        },
        brdstart: {
            required: "This field is required",
            date: "Incorrect date format"
        },
        desc: {
            required: "This field is required",
            maxlength: "Description cannot exceed 1024 characters"
        },
        poster: {
            required: "This field is required"
        }
    },
    submitHandler: commonSeriesSubmitHandler
};

$("#form-addSeries").validate(commonSeriesValidationOptions);
$("#form-editSeries").validate(commonSeriesValidationOptions);

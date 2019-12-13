$(".signup_action_button").on("click", function(e) {
    var error = false;
    $(".signup_main_container").find(".input_error").removeClass("input_error");
    $(".signup_main_container").find(".validation_advice").remove();
    var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var email = $(".user_name");
    if ($(".full_name").val().trim() == "") {
        error = true;
        $(".full_name").addClass("input_error");
        $(".full_name").after("<div class='validation_advice'>Full Name is a required field</div>");
    }
    if (email.val().trim() == "") {
        error = true;
        email.addClass("input_error");
        email.after("<div class='validation_advice'>Email is a required field</div>");
    } else if (!emailRegex.test(email.val().trim())) {
        error = true;
        email.addClass("input_error");
        email.after("<div class='validation_advice'>Please provide valid email</div>");
    }
    if ($(".user_password").val().trim() == "") {
        error = true;
        $(".user_password").addClass("input_error");
        $(".user_password").after("<div class='validation_advice'>Password is a required field</div>");
    } else if ($(".user_confirm_password").val().trim() == "") {
        error = true;
        $(".user_confirm_password").addClass("input_error");
        $(".user_confirm_password").after("<div class='validation_advice'>Confirm Password is a required field</div>");
    }
    if ($(".user_password").val().trim() != "" && $(".user_password").val().trim().length < 8) {
        error = true;
        $(".user_password").addClass("input_error");
        $(".user_password").after("<div class='validation_advice'>Password should be atleast 8 character long</div>");
    }
    if ($(".user_password").val().trim() != "" && $(".user_confirm_password").val().trim() != "" && $(".user_password").val().trim() != $(".user_confirm_password").val().trim()) {
        error = true;
        $(".user_confirm_password").addClass("input_error");
        $(".user_confirm_password").after("<div class='validation_advice'>Confirm Password should be same as Password</div>");
    }
    if (error) {
        e.preventDefault();
    };
});

$(".signin_button").on("click", function() {
    location.href = loginUrl;
});
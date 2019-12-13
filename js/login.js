$(".login_button").on("click", function(e) {
    var error = false;
    $(".login_main_container").find(".input_error").removeClass("input_error");
    $(".login_main_container").find(".validation_advice").remove();
    var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var email = $(".user_name");
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
    }
    if (error) {
        e.preventDefault();
    };
});

$(".signup_button").on("click", function() {
    location.href = signupUrl;
});

$(".forgot_password_link").on("click", function(){
    $(".modalbox_overlay").fadeIn();
    $(".modalbox").animate({"top":"50%"});
});

$(".modalbox_overlay").on("click", function(e){
    if (e.target === this) {
        $(".modalbox").animate({"top":"-50%"}, function(){
            $(".modalbox_overlay").fadeOut();
        });
    }
});

$(document).on("click", ".modalbox_footer_button", function(){
    var error = false;
    $(".modalbox_overlay").find(".input_error").removeClass("input_error");
    $(".modalbox_overlay").find(".validation_advice").remove();
    var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var email = $(".forgot_password_email");
    if (email.val().trim() == "") {
        error = true;
        email.addClass("input_error");
        email.after("<div class='validation_advice'>Email is a required field</div>");
    } else if (!emailRegex.test(email.val().trim())) {
        error = true;
        email.addClass("input_error");
        email.after("<div class='validation_advice'>Please provide valid email</div>");
    }
    if (!error) {
        $(".loader_container").show();
        $.ajax({
            url: "sendforgotpasswordmail",
            type: "POST",
            dataType: "json",
            data: {
                emailAddress: email.val().trim()
            },
            success: function(apiData){
                $(".loader_container").hide();
                $(".modalbox").animate({"top":"-50%"}, function(){
                    $(".modalbox_overlay").fadeOut();
                });
            },
            error: function(){
                $(".loader_container").hide();
                $(".modalbox").animate({"top":"-50%"}, function(){
                    $(".modalbox_overlay").fadeOut();
                });
            }
        });
    }
});
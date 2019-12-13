$(document).ready(function(){
    $(".accinfo_modalbox_overlay").fadeIn();
    $(".accinfo_modalbox").animate({"top":"50%"});
});

$(document).on("click", ".accinfo_modalbox_footer_button", function(){
    var error = false, errormsg = "", otpField = $("#otp"), passField = $("#new_password"), confpassField = $("#new_confirmpassword");
    $(".modalbox").find(".input_error").removeClass("input_error");
    if (otpField.val().trim() == "") {
        error = true;
        errormsg = "OTP is required field.";
        otpField.addClass("input_error");
        $("html, body").animate({
            scrollTop: otpField.offset().top
        }, 300);
    } else if (passField.val().trim() == "") {
        error = true;
        errormsg = "New Password is required field.";
        passField.addClass("input_error");
        $("html, body").animate({
            scrollTop: passField.offset().top
        }, 300);
    } else if (passField.val().trim().length < 8) {
        error = true;
        errormsg = "Password should be atleast 8 character long.";
        passField.addClass("input_error");
        $("html, body").animate({
            scrollTop: passField.offset().top
        }, 300);
    } else if (confpassField.val().trim() == "") {
        error = true;
        errormsg = "Confirm Password is required field.";
        confpassField.addClass("input_error");
        $("html, body").animate({
            scrollTop: confpassField.offset().top
        }, 300);
    } else if (confpassField.val().trim() != passField.val().trim()) {
        error = true;
        errormsg = "Password & Confirm Password should match.";
        confpassField.addClass("input_error");
        $("html, body").animate({
            scrollTop: confpassField.offset().top
        }, 300);
    }
    if (!error) {
        $(".loader_container").show();
        $.ajax({
            url: "resetpassword",
            type: "POST",
            dataType: "json",
            data: {
                otp :otpField.val().trim(),
                password: passField.val().trim(),
                confpassword: confpassField.val().trim()
            },
            success: function(apiData){
                $(".loader_container").hide();
                if (apiData["success"]) {
                    successNotification(apiData["message"]);
                    setTimeout(function(){
                        location.href = loginUrl;
                    }, 2000);
                } else {
                    errorNotification(apiData["message"]);
                }
            },
            error: function(){
                $(".loader_container").hide();
                errorNotification("Sorry something went wrong.");
            }
        });
    } else {
        errorNotification(errormsg);
    }
});

function errorNotification(content)
{
    $(".modalbox_message_indicator").text("error_outline");
    $(".modalbox_content").text(content);
    $(".modalbox_title").text("Error Message");
    $(".modalbox_overlay").fadeIn();
    $(".modalbox").animate({"top":"50%"});
    $(".modalbox_message_indicator").removeClass("success_title").addClass("error_title");
}

function successNotification(content)
{
    $(".modalbox_message_indicator").text("check_circle_outline");
    $(".modalbox_content").text(content);
    $(".modalbox_title").text("Success Message");
    $(".modalbox_overlay").fadeIn();
    $(".modalbox").animate({"top":"50%"});
    $(".modalbox_message_indicator").removeClass("error_title").addClass("success_title");
}
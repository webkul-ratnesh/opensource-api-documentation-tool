<?php
// @codingStandardsIgnoreStart
session_start();
if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
    $link = "https"; 
} else {
    $link = "http";
}
$link .= "://";
$link .= $_SERVER["HTTP_HOST"]."/";
$link .= explode("/", $_SERVER["REQUEST_URI"])[1];
if (!isset($_SESSION["userName"]) && $_SESSION["userName"] == "") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <link rel="shortcut icon" type="image/png" href="images/favicon.png"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="css/styles.css?v=1.3" rel="stylesheet"/>
        <title>Login</title>
        <script type="text/javascript">
            if (typeof jQuery == "undefined") {
                document.write(unescape("%3Cscript src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' type='text/javascript'%3E%3C/script%3E"));
            }
        </script>
        <script type="text/javascript" charset="utf-8" async="" src="js/login.js?v=1.3"></script>
    </head>
    <body>
<?php   if ($_SESSION["errormsg"]) {    ?>
            <div class="session_error_message"><?= $_SESSION["errormsg"]; ?></div>
<?php       unset($_SESSION["errormsg"]);
        }   ?>
        <div class="loader_container">
            <div class="cp-spinner cp-meter"></div>
        </div>
        <div class="modalbox_overlay">
            <div class="modalbox">
                <div class="modalbox_head">
                    <i class="material-icons modalbox_message_indicator error_title">error_outline</i>
                    <label class="modalbox_title">Forgot Password</label>
                </div>
                <div class="modalbox_body">
                    <p class="modalbox_content">
                        <input type="text" placeholder="Enter your email" class="forgot_password_email input_field"/>
                    </p>
                </div>
                <div class="modalbox_footer">
                    <button type="button" class="modalbox_footer_button">Ok</button>
                </div>
            </div>
        </div>
        <div class="login_main_container">
            <div id="logo_container">
                <div class="logo_box">
                    <img src="<?= "images/logo.svg"; ?>" class="logo"/>
                </div>
                <div class="logo_box">
                    <label class="logo_label">Mobikul</label>
                    <label class="logo_sub_label">API</label>
                </div>
            </div>
            <form method="post" action="loginpost">
                <div class="input_container">
                    <label class="input_label">Email ID</label>
                    <input type="text" name="username" class="user_name input_field" placeholder="Username/Email" value="test@example.com"/>
                </div>
                <div class="input_container">
                    <label class="input_label">Password</label>
                    <input type="password" name="password" class="user_password input_field" placeholder="Password" value="test123"/>
                </div>
                <div class="input_container forgot_password_link_container">
                    <label class="input_label forgot_password_link">Forgot Your Password?</label>
                </div>
                <div class="input_container">
                    <button type"submit" class="login_button">Login</button>
                    <label class="signup_button">SignUp</label>
                </div>
            </form>
        </div>
    </body>
</html>
<?php
} else {
    header("Location: ".$link."/dashboard");
}
?>
<script type="text/javascript">
    var signupUrl = "<?= $link; ?>/signup";
</script>
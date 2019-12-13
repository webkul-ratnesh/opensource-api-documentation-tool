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
        <script type="text/javascript" charset="utf-8" async="" src="js/signup.js?v=1.3"></script>
    </head>
    <body>
<?php   if ($_SESSION["errormsg"]) { ?>
            <div class="session_error_message"><?= $_SESSION["errormsg"]; ?></div>
<?php       unset($_SESSION["errormsg"]);
        }   ?>
        <div class="signup_main_container">
            <div id="logo_container">
                <div class="logo_box">
                    <img src="<?= "images/logo.svg"; ?>" class="logo"/>
                </div>
                <div class="logo_box">
                    <label class="logo_label">mobikul</label>
                    <label class="logo_sub_label">API</label>
                </div>
            </div>
            <form method="post" action="signuppost">
                <div class="input_container">
                    <label class="input_label">Full Name</label>
                    <input type="text" name="name" class="full_name input_field" placeholder="Name"/>
                </div>
                <div class="input_container">
                    <label class="input_label">Email ID</label>
                    <input type="text" name="username" class="user_name input_field" placeholder="Username/Email"/>
                </div>
                <div class="input_container">
                    <label class="input_label">Password</label>
                    <input type="password" name="password" class="user_password input_field" placeholder="Password"/>
                </div>
                <div class="input_container">
                    <label class="input_label">Confirm Password</label>
                    <input type="password" name="conf_password" class="user_confirm_password input_field" placeholder="Confirm Password"/>
                </div>
                <div class="input_container">
                    <button type="submit" class="signup_action_button">Create an Account</button>
                    <label class="signin_button">Login</label>
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
    var loginUrl = "<?= $link; ?>/login";
</script>
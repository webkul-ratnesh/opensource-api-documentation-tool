<?php
// @codingStandardsIgnoreStart
session_start();
$link = "http";
if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
    $link = "https"; 
}
$link .= "://";
$link .= $_SERVER["HTTP_HOST"]."/";
$link .= explode("/", $_SERVER["REQUEST_URI"])[1];
if (isset($_SESSION["userName"]) && $_SESSION["userName"] != "") {
    header("Location: ".$link."/dashboard");
}
if (isset($_POST["otp"])) {
    if (isset($_POST["password"]) && isset($_SESSION["otp"]) && isset($_SESSION["resetfor"]) && $_SESSION["otp"] == $_POST["otp"]) {
        $file = "db/users/users.json";
        $userDataInArray = json_decode(file_get_contents($file), true);
        foreach ($userDataInArray as $key => $eachUser) {
            if ($eachUser["username"] == $_SESSION["resetfor"]) {
                $userDataInArray[$key]["password"] = md5($_POST["password"]);
                unlink($_SESSION["otp"]);
                unlink($_SESSION["resetfor"]);
            }
        }
        $updatedJsonData = str_replace("\/", "/", json_encode($userDataInArray));
        file_put_contents($file, $updatedJsonData);
        echo json_encode(
            [
                "success" => true,
                "message" => "Password reset successfully."
            ],
            true
        );
        return;
    }
} else { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <link rel="shortcut icon" type="image/png" href="images/favicon.png"/>
        <link rel="shortcut icon" type="image/png" href="https://miad.mobikul.com/images/favicon.png"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="css/styles.css?v=1.3" rel="stylesheet"/>
        <title>Reset Passowrd</title>
        <script type="text/javascript">
            if (typeof jQuery == "undefined") {
                document.write(unescape("%3Cscript src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' type='text/javascript'%3E%3C/script%3E"));
            }
        </script>
        <script type="text/javascript" charset="utf-8" async="" src="js/resetpassword.js?v=1.3"></script>
    </head>
    <body>
        <div class="modalbox_overlay">
            <div class="modalbox">
                <div class="modalbox_head">
                    <i class="material-icons modalbox_message_indicator success_title">check_circle_outline</i>
                    <label class="modalbox_title"></label>
                </div>
                <div class="modalbox_body">
                    <p class="modalbox_content"></p>
                </div>
                <div class="modalbox_footer">
                    <button type="button" class="modalbox_footer_button">Ok</button>
                </div>
            </div>
        </div>
        <div class="accinfo_modalbox_overlay">
            <div class="accinfo_modalbox">
                <div class="accinfo_modalbox_head">
                    <i class="material-icons accinfo_modalbox_message_indicator success_title" style="float:left">check_circle_outline</i>
                    <label style="margin-left:50px" class="accinfo_modalbox_title">Set New Password</label>
                </div>
                <div class="accinfo_modalbox_body">
                    <div class="accinfo_eachrow">
                        <div class="accinfo_eachrow_left">
                            <label>OTP</label>
                        </div>
                        <div class="accinfo_eachrow_right">
                            <input type="text" id="otp" placeholder="OTP"/>
                        </div>
                    </div>
                    <div class="accinfo_eachrow">
                        <div class="accinfo_eachrow_left">
                            <label>New Password</label>
                        </div>
                        <div class="accinfo_eachrow_right">
                            <input type="password" id="new_password" placeholder="New Password"/>
                        </div>
                    </div>
                    <div class="accinfo_eachrow">
                        <div class="accinfo_eachrow_left">
                            <label>Confirm Password</label>
                        </div>
                        <div class="accinfo_eachrow_right">
                            <input type="password" id="new_confirmpassword" placeholder="Confirm Password"/>
                        </div>
                    </div>
                </div>
                <div class="accinfo_modalbox_footer">
                    <button type="button" class="accinfo_modalbox_footer_button">Submit</button>
                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    var loginUrl = "<?= $link; ?>/login";
</script>
<?php
} ?>
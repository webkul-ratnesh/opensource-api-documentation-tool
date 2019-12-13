<?php
// @codingStandardsIgnoreStart
session_start();
if (isset($_SESSION["userName"]) && $_SESSION["userName"] != "") {
    $allDb = glob("db/*.json");   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <link rel="shortcut icon" type="image/png" href="images/favicon.png"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="css/styles.css?v=1.1" rel="stylesheet"/>
        <title>Create Document</title>
        <script type="text/javascript">
            if (typeof jQuery == "undefined") {
                document.write(unescape("%3Cscript src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' type='text/javascript'%3E%3C/script%3E"));
            }
        </script>
        <script type="text/javascript" charset="utf-8" async="" src="js/main.js?v=1.0"></script>
    </head>
    <body>

    </body>
</html>
<?php
} else {
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
        $link = "https"; 
    } else {
        $link = "http";
    }
    $link .= "://";
    $link .= $_SERVER["HTTP_HOST"]."/";
    $link .= explode("/", $_SERVER["REQUEST_URI"])[1];
    header("Location: ".$link."/login");
} ?>
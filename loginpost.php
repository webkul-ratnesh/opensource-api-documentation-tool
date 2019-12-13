<?php 
if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
    $link = "https"; 
} else {
    $link = "http";
}
$link .= "://";
$link .= $_SERVER["HTTP_HOST"]."/";
$link .= explode("/", $_SERVER["REQUEST_URI"])[1];

if (!isset($_SESSION["userName"]) && $_SESSION["userName"] == "") {
    $userName = $_POST["username"];
    $password = $_POST["password"];
    $file = "db/users/users.json";
    $users = json_decode(file_get_contents($file), true);
    $valid = false;
    $name = ""; $id = "";
    foreach ($users as $eachUser) {
        if ($eachUser["username"] == $userName && $eachUser["password"] == md5($password)) {
            $valid = true;
            $id = $eachUser["id"];
            $name = $eachUser["name"];
        }
    }
    if ($valid) {
        session_start();
        $_SESSION["id"] = $id;
        $_SESSION["name"] = $name;
        $_SESSION["userName"] = $userName;
        header("Location: ".$link."/dashboard");
    } else {
        session_start();
        $_SESSION["errormsg"] = "Invalid Credentials.";
        header("Location: ".$link."/login");
    }
} else {
    header("Location: ".$link."/dashboard");
}
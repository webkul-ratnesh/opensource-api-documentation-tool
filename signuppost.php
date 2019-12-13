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
    $name = $_POST["name"];
    $userName = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["conf_password"];
    if ($userName == "" || $password == "" || $confirmPassword == "") {
        session_start();
        $_SESSION["errormsg"] = "Invalid Request.";
        header("Location: ".$link."/signup");
        return;
    }
    $file = "db/users/users.json";
    $users = json_decode(file_get_contents($file), true);
    $alreadyExist = false;
    foreach ($users as $eachUser) {
        if ($eachUser["username"] == $userName) {
            $alreadyExist = true;
        }
    }
    if ($alreadyExist) {
        session_start();
        $_SESSION["errormsg"] = "User Already Exist.";
        header("Location: ".$link."/signup");
    } else {
        $id = time();
        $users[] = [
            "id" => $id,
            "name" => $name,
            "username" => $userName,
            "password" => md5($password)
        ];
        $users = json_encode($users, true);
        file_put_contents($file, $users);
        session_start();
        $_SESSION["id"] = $id;
        $_SESSION["name"] = $name;
        $_SESSION["userName"] = $userName;
        header("Location: ".$link."/dashboard");
    }
} else {
    header("Location: ".$link."/dashboard");
}
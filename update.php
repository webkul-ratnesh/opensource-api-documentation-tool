<?php
// @codingStandardsIgnoreStart
$file = "db/users/users.json";
$userDataInArray = json_decode(file_get_contents($file), true);
session_start();
header("Content-Type: application/json");
$logger = new Logger();
if (isset($_SESSION["userName"]) && $_SESSION["userName"] != "") {
    $message = "Profile details updated.";
    $data = $_POST;
    $userName = $data["userName"];
    $sessionId = $data["sessionId"];
    $userEmail = $data["userEmail"];
    $userPassword = $data["userPassword"];
    if ($_SESSION["id"] === $sessionId) {
        foreach ($userDataInArray as $key => $eachUser) {
            if ($eachUser["id"] == $_SESSION["id"]) {
                $userDataInArray[$key]["name"] = $userName;
                $userDataInArray[$key]["username"] = $userEmail;
                $_SESSION["name"] = $userName;
                $_SESSION["userName"] = $userEmail;
                if ($userPassword != "") {
                    $userDataInArray[$key]["password"] = md5($userPassword);
                }
            }
        }
    }
    $updatedJsonData = str_replace("\/", "/", json_encode($userDataInArray));
///////////////////////////////////////////////////////////////////////////
$logger->printLog("By : ".$_SESSION["userName"]);
$logger->printLog("Date : ".date("Y-m-d h:i:sa", time()));
$logger->printLog("Content :".json_encode($userDataInArray)."\n\n");
///////////////////////////////////////////////////////////////////////////
    file_put_contents($file, $updatedJsonData);
    echo json_encode(
        [
            "success" => true,
            "message" => $message
        ],
        true
    );
    return;
} else {
    echo json_encode(
        [
            "success" => false,
            "message" => "Session Expired"
        ],
        true
    );
    return;
}

class Logger
{

    function printLog($data)
    {
        $todayFile = "log/mad_".date("Y_m_d").".log";
        if (!is_file($todayFile)) {
            $fp = fopen($todayFile, "w");
            fwrite($fp, "");
            fclose($fp);
        }
        $fileContent = file_get_contents($todayFile);
        if (is_array($data) || is_object($data)) {
            $data = print_r($data, true);
        }
        $data = $fileContent."\n".$data;
        file_put_contents($todayFile, $data);
    }

}
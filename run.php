<?php

$requestData = $_POST;
$error = false;
$errorMessage = [];

if (!isset($requestData["method"])) {
    $error = true;
    $errorMessage[] = "Request Method is a required field.";
}
if (!isset($requestData["urlEndPoint"])) {
    $error = true;
    $errorMessage[] = "Request Url is a required field.";
}

if ($error) {
    header("Content-Type: application/json");
    echo json_encode(
        implode("<br>", $errorMessage),
        true
    );
    return;
}

$url = $requestData["urlEndPoint"]."?";
$ch = curl_init();
if ($requestData["method"] == "POST") {
    curl_setopt(
        $ch,
        CURLOPT_POST,
        1
    );
    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        http_build_query(
            $requestData["params"]
        )
    );
} elseif ($requestData["method"] == "GET") {
    $url .= http_build_query($requestData["params"]);
}
curl_setopt(
    $ch,
    CURLOPT_URL,
    $url
);
curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);
curl_setopt(
    $ch,
    CURLOPT_COOKIESESSION,
    true
);
if (!is_file("/tmp/".md5($_SERVER["HTTP_HOST"].$_SERVER["HTTP_USER_AGENT"]))) {
    curl_setopt(
        $ch,
        CURLOPT_COOKIEJAR,
        "/tmp/".md5($_SERVER["HTTP_HOST"].$_SERVER["HTTP_USER_AGENT"])
    );
} else {
    curl_setopt(
        $ch,
        CURLOPT_COOKIEJAR,
        "PHPSESSID"
    );
}
curl_setopt(
    $ch,
    CURLOPT_COOKIEFILE,
    "/tmp/".md5($_SERVER["HTTP_HOST"].$_SERVER["HTTP_USER_AGENT"])
);
$headerString = [];
foreach ($requestData["headers"] as $key => $value) {
    $headerString[] = $key.":".$value;
}
curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    $headerString
);
curl_setopt(
    $ch,
    CURLOPT_HEADERFUNCTION,
    function ($curl, $header) use (&$headers)
    {
        $len = strlen($header);
        $header = explode(":", $header, 2);
        if (count($header) < 2) {
            return $len;
        }
        $headers[strtolower(trim($header[0]))] = trim($header[1]);
        return $len;
    }
);
$start = round(microtime(true) * 1000);
$response = curl_exec($ch);
$end = round(microtime(true) * 1000);


$returnData = [];
header("Content-Type: application/json");
$returnData["token"] = $headers["token"] ?? "";
$returnData["timetaken"] = $end-$start;
$returnData["data"] = json_decode($response, true);
$returnData["size"] = strlen($response);
$returnData["responseCode"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($returnData["responseCode"] == 401) {
    unlink("/tmp/".md5($_SERVER["HTTP_HOST"].$_SERVER["HTTP_USER_AGENT"]));
}
curl_close($ch);
echo json_encode($returnData, true);
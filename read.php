<?php

$file = "db/".$_POST["db"].".json";
$apiDocumentInArray = json_decode(file_get_contents($file), true);

if ($_POST["for"] == "read") {
    $group = $apiDocumentInArray["group-list"][$_POST["groupIndex"]];
    $api = $group["api-list"][$_POST["apiIndex"]];
    $api["group-name"] = $group["group"];
    $api["group-description"] = $group["description"];
    header("Content-Type: application/json");
    echo json_encode($api, true);
}
if ($_POST["for"] == "edit") {
    $group = $apiDocumentInArray["group-list"][$_POST["groupIndex"]];
    $api = $group["api-list"][$_POST["apiIndex"]];
    $api["group-name"] = $group["group"];
    $api["group-description"] = $group["description"];
    header("Content-Type: application/json");
    echo json_encode($api, true);
}
if ($_POST["for"] == "run") {
    $group = $apiDocumentInArray["group-list"][$_POST["groupIndex"]];
    $api = $group["api-list"][$_POST["apiIndex"]];
    $api["domain"] = $apiDocumentInArray["main-domain"];
    unset($api["api-description"]);
    unset($api["api-name"]);
    unset($api["api-response"]);
    unset($api["possible-values"]);
    unset($api["response-header-params"]);
    header("Content-Type: application/json");
    echo json_encode($api, true);
}
if ($_POST["for"] == "add") {
    $group = $apiDocumentInArray["group-list"][$_POST["groupIndex"]];
    $api["group-name"] = $group["group"];
    $api["group-description"] = $group["description"];
    header("Content-Type: application/json");
    echo json_encode($api, true);
}
<?php

session_start();
session_destroy();
if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
    $link = "https"; 
} else {
    $link = "http";
}
$link .= "://";
$link .= $_SERVER["HTTP_HOST"]."/";
$link .= explode("/", $_SERVER["REQUEST_URI"])[1];
header("Location: ".$link."/login");
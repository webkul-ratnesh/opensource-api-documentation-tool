<?php
// @codingStandardsIgnoreStart
$file = "db/".$_POST["db"].".json";
$apiDocumentInArray = json_decode(file_get_contents($file), true);
session_start();
header("Content-Type: application/json");
$logger = new Logger();
if (isset($_SESSION["userName"]) && $_SESSION["userName"] != "") {
    $message = "";
    $data = $_POST;
    $action = $data["action"];
    if ($action == "createnew") {
        if (isset($data["copyof"]) && $data["copyof"] == "") {
            $retrunData = [
                "success" => true,
                "message" => "New document has been created successfuly."
            ];
            $tempData = [
                "main-domain" => $data["domainUrl"],
                "main-description" => $data["docDescription"],
                "access" => [
                    $_SESSION["userName"]
                ],
                "group-list"=> [
                    [
                        "group" => $data["docGroupName"],
                        "description" => $data["docGroupDescription"],
                        "api-list" => [

                        ]
                    ]
                ]
            ];
            $newDocName = "db/".strtolower($data["docName"]).".json";
            if (!is_file($newDocName)) {
                $fp = fopen($newDocName, "w");
                fwrite($fp, json_encode($tempData));
                fclose($fp);
            } else {
                $retrunData = [
                    "success" => false,
                    "message" => "Suggest another doc name, this name is already taken."
                ];
            }
            echo json_encode(
                $retrunData,
                true
            );
            return;
        } else {
            $copyofDoc = "db/".$data["copyof"].".json";
            if (is_file($copyofDoc)) {
                $copyDocInArray = json_decode(file_get_contents($copyofDoc), true);
                $tmp = $copyDocInArray["access"];
                if (!in_array($_SESSION["userName"], $tmp)) {
                    $tmp[] = $_SESSION["userName"];
                    $copyDocInArray["access"] = $tmp;
                }
                $copytoDoc = "db/".strtolower($data["docName"]).".json";
                $fp = fopen($copytoDoc, "w");
                fwrite($fp, json_encode($copyDocInArray));
                fclose($fp);
                $retrunData = [
                    "success" => true,
                    "message" => "Copy of selected doc is created."
                ];
            } else {
                $retrunData = [
                    "success" => false,
                    "message" => "Invalid database."
                ];
            }
            echo json_encode(
                $retrunData,
                true
            );
            return;
        }
    } elseif (!in_array($_SESSION["userName"], $apiDocumentInArray["access"])) {
        echo json_encode(
            [
                "success" => false,
                "message" => "Sorry you don't have write permission for this database, Please cotact Admin."
            ],
            true
        );
        return;
    }
    $apiIndex = $data["apiIndex"];
    $groupIndex = $data["groupIndex"];
    $changedGroup = $data["changedGroup"];
    $apiData = json_decode($data["data"], true);
    $apiData["api-list"][0]["possible-values"] = json_decode($apiData["api-list"][0]["possible-values"], true);
    if ($action == "edit") {
        if ($apiIndex != "" && $changedGroup == "") {
            // $apiDocumentInArray["group-list"][$groupIndex]["api-list"][1] = $apiData["api-list"][0];
        } else {
            $apiDocumentInArray["group-list"][$groupIndex]["api-list"][] = $apiData["api-list"][0];
            if ($changedGroup != "") {
                unset($apiDocumentInArray["group-list"][$changedGroup]["api-list"][(int)$apiIndex]);
            }
        }
        $message = "Api Updated Successfully.";
    } elseif ($action == "new") {
        if ($groupIndex == "new") {
            $apiDocumentInArray["group-list"][] = $apiData;
        } else {
            $apiDocumentInArray["group-list"][$groupIndex]["api-list"][] = $apiData["api-list"][0];
        }
        $message = "Api Created Successfully.";
    }
    $updatedJsonData = str_replace("\/", "/", json_encode($apiDocumentInArray));
    $updatedJsonData = str_replace("\u003C", "<", $updatedJsonData);
    $updatedJsonData = str_replace("\u003E", ">", $updatedJsonData);

///////////////////////////////////////////////////////////////////////////
$logger->printLog("By : ".$_SESSION["userName"]);
$logger->printLog("Date : ".date("Y-m-d h:i:sa", time()));
$logger->printLog("Content :".json_encode($apiDocumentInArray)."\n\n");
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
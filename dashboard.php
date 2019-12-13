<?php
// @codingStandardsIgnoreStart
session_start();
if (isset($_SESSION["userName"]) && $_SESSION["userName"] != "") {
    $allDb = glob("db/*.json");
    $file = $allDb[0];
    if (isset($_GET["db"]) && $_GET["db"] != "") {
        $file = "db/".$_GET["db"].".json";
    }
    $apiDocumentInArray = json_decode(file_get_contents($file), true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <link rel="shortcut icon" type="image/png" href="images/favicon.png"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="css/styles.css?v=1.3" rel="stylesheet"/>
        <link href="css/jquery.json-viewer.css?v=1.3" rel="stylesheet"/>
        <title>Create Document</title>
        <script type="text/javascript">
            if (typeof jQuery == "undefined") {
                document.write(unescape("%3Cscript src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' type='text/javascript'%3E%3C/script%3E"));
            }
        </script>
        <script type="text/javascript" charset="utf-8" async="" src="js/dashboard.js?v=1.3"></script>
        <script type="text/javascript" charset="utf-8" async="" src="js/jquery.json-viewer.js"></script>
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
                    <label class="accinfo_modalbox_title">Account Information</label>
                </div>
                <div class="accinfo_modalbox_body">
                    <div class="accinfo_eachrow">
                        <div class="accinfo_eachrow_left">
                            <label>Full Name</label>
                        </div>
                        <div class="accinfo_eachrow_right">
                            <input value="<?= $_SESSION["name"]; ?>" type="text" id="accinfo_name" placeholder="Full Name"/>
                        </div>
                    </div>
                    <div class="accinfo_eachrow">
                        <div class="accinfo_eachrow_left">
                            <label>Email</label>
                        </div>
                        <div class="accinfo_eachrow_right">
                            <input value="<?= $_SESSION["userName"]; ?>" type="text" id="accinfo_email" placeholder="Email"/>
                        </div>
                    </div>
                    <div class="accinfo_eachrow">
                        <div class="accinfo_eachrow_left">
                            <label>Password</label>
                        </div>
                        <div class="accinfo_eachrow_right">
                            <input type="password" id="accinfo_password" placeholder="Password"/>
                        </div>
                    </div>
                    <div class="accinfo_eachrow">
                        <div class="accinfo_eachrow_left">
                            <label>Confirm Password</label>
                        </div>
                        <div class="accinfo_eachrow_right">
                            <input type="password" id="accinfo_confirmpassword" placeholder="Confirm Password"/>
                        </div>
                    </div>
                </div>
                <div class="accinfo_modalbox_footer">
                    <button type="button" class="accinfo_modalbox_footer_button">Update</button>
                </div>
            </div>
        </div>
        <div class="newdoc_modalbox_overlay">
            <div class="newdoc_modalbox">
                <div class="newdoc_modalbox_head">
                    <label class="newdoc_modalbox_title">Create Document</label>
                </div>
                <div class="newdoc_modalbox_body">
                    <div class="newdoc_eachrow">
                        <div class="newdoc_eachrow_left">
                            <label>Make copy of</label>
                        </div>
                        <div class="newdoc_eachrow_right blue copydb_field">
                            <select id="newdoc_doc_copy">
                                <option value="">---None---</option>
<?php                           foreach ($allDb as $filename) {
                                    if (is_file($filename)) {
                                        $name = reset(explode(".", end(explode("/", $filename))));
                                        echo '<option value="'.$name.'">'.ucfirst($name).'</option>';
                                    }
                                }       ?>
                            </select>
                            <i class="material-icons form_selection_icon">keyboard_arrow_down</i>
                        </div>
                    </div>
                    <div class="newdoc_eachrow">
                        <div class="newdoc_eachrow_left">
                            <label>Document Name</label>
                        </div>
                        <div class="newdoc_eachrow_right">
                            <input value="" type="text" id="newdoc_doc_name" placeholder="Document Name"/>
                        </div>
                    </div>
                    <div class="newdoc_eachrow">
                        <div class="newdoc_eachrow_left">
                            <label>Domain Url</label>
                        </div>
                        <div class="newdoc_eachrow_right">
                            <input value="" type="text" id="newdoc_domain_url" placeholder="Domain Url"/>
                        </div>
                    </div>
                    <div class="newdoc_eachrow">
                        <div class="newdoc_eachrow_left">
                            <label>Document Description</label>
                        </div>
                        <div class="newdoc_eachrow_right">
                            <textarea id="newdoc_description" placeholder="Document Description"></textarea>
                        </div>
                    </div>
                    <div class="newdoc_eachrow">
                        <div class="newdoc_eachrow_left">
                            <label>Group Name</label>
                        </div>
                        <div class="newdoc_eachrow_right">
                            <input value="" type="text" id="newdoc_group_name" placeholder="Group Name"/>
                        </div>
                    </div>
                    <div class="newdoc_eachrow">
                        <div class="newdoc_eachrow_left">
                            <label>Group Description</label>
                        </div>
                        <div class="newdoc_eachrow_right">
                            <textarea id="newdoc_group_description" placeholder="Group Description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="newdoc_modalbox_footer">
                    <button type="button" class="newdoc_modalbox_footer_button">Create</button>
                </div>
            </div>
        </div>
        <div id="main_container">
            <div id="left_container" class="sibling_container">
                <div id="logo_container">
                    <div class="logo_box">
                        <img src="<?= "images/logo.svg"; ?>" class="logo"/>
                    </div>
                    <div class="logo_box">
                        <label class="logo_label">mobikul</label>
                        <label class="logo_sub_label">API</label>
                    </div>
                </div>
                <div class="action_list">
                    <label class="action_label" data-for="api_list">API List</label>
                    <label class="action_label" data-for="add_api">Add API</label>
                    <label class="action_label" data-for="run_add_api">Run & Add API</label>
                </div>
            </div>
            <div id="right_container" class="sibling_container dashboard_right_container">
                <div class="loader_container">
                    <div class="cp-spinner cp-meter"></div>
                </div>
                <div id="header">
                    <div class="header_sibling_container">
                        <label class="group_label">API LIST</label>
                    </div>
                    <div class="header_sibling_container new_document_icon_container">
                        <i title='Create New Document' class='material-icons new_document'>note_add</i>
                    </div>
                    <div class="header_sibling_container db_selection_container">
                        <div class="styled-select blue semi-square">
                            <select class="form_selection form_select">
<?php                       foreach ($allDb as $filename) {
                                if (is_file($filename)) {
                                    $name = reset(explode(".", end(explode("/", $filename))));
                                    if ($file == $filename) {
                                        echo '<option selected="selected" value="'.$name.'">'.ucfirst($name).'</option>';
                                    } else {
                                        echo '<option value="'.$name.'">'.ucfirst($name).'</option>';
                                    }
                                }
                            }       ?>
                            </select>
                            <i class="material-icons form_selection_icon">keyboard_arrow_down</i>
                        </div>
                    </div>
                    <div class="header_sibling_container search_bar_container">
                        <div class="search_bar">
                            <input placeholder="Search by API name" type="text" class="search_input" value="<?= $_GET["q"] ?? ""; ?>"/>
                            <i class="material-icons search_button">search</i>
                        </div>
                    </div>
                    <div class="header_sibling_container logout_button_container button_container">
                        <i class="material-icons logout_button" title="Logout">power_settings_new</i>
                    </div>
                    <div class="header_sibling_container accinfo_button_container button_container">
                        <i class="material-icons accountinfo_button" title="Account Information">settings</i>
                    </div>
                </div>
                <div>
                    <div class="api_list_conatiner">
                        <table class="api_list_table" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>NAME</td>
                                    <td>GROUP</td>
                                    <td>ACTION</td>
                                </tr>
                            </thead>
                            <tbody>
<?php                           $pageNumber = 1;
                                $limitPerPage = 10;
                                if (isset($_GET["p"]) && $_GET["p"] > 1) {
                                    $pageNumber = $_GET["p"];
                                }
                                if ($pageNumber == 1) {
                                    $startLimit = 1;
                                    $endLimit = $limitPerPage;
                                } else {
                                    $startLimit = ($limitPerPage*($pageNumber-1))+1;
                                    $endLimit = ($limitPerPage*$pageNumber);
                                }
                                $counter = 1;
                                foreach ($apiDocumentInArray["group-list"] as $key => $eachGroup) {
                                    foreach ($eachGroup["api-list"] as $internalKey => $eachApi) {
                                        $searchQuery = $_GET["q"] ?? $eachApi["api-name"];
                                        if (strpos(strtolower($eachApi["api-name"]), strtolower($searchQuery)) !== false) {
                                            if ($counter >= $startLimit && $counter <= $endLimit) {
                                                echo "<tr data-groupindex='".$key."' data-apiindex='".$internalKey."'>";
                                                    echo "<td>".($counter)."</td>";
                                                    echo "<td>".$eachApi["api-name"]."</td>";
                                                    echo "<td>".$eachGroup["group"]."</td>";
                                                    echo "<td>";
                                                        echo "<i title='Run' class='material-icons api_list_action api_run'>double_arrow</i>";
                                                        echo "<i title='Edit' class='material-icons api_list_action api_edit'>edit</i>";
                                                        echo "<i title='Delete' class='material-icons api_list_action api_delete'>delete_forever</i>";
                                                    echo "</td>";
                                                echo "</tr>";
                                            }
                                            $counter++;
                                        }
                                    }
                                }
                                if ($counter == 1) {
                                    echo "<tr><td colspan='4'><b>We couldn't find any records.</b></td></tr>";
                                }   ?>
                            </tbody>
                        </table>
<?php                   if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
                            $currentUrl = "https";
                        } else {
                            $currentUrl = "http";
                        }
                        $db = "";
                        $adjacents = 3;
                        $searchQuery = "";
                        $currentUrl .= "://";
                        $currentUrl .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
                        $parts = parse_url($currentUrl);
                        parse_str($parts["query"], $query);
                        $p = $query["p"] ?? 1;
                        $db = $query["db"] ?? "";
                        $searchQuery = $query["q"] ?? "";
                        $params = [];
                        if ($db != "") {
                            $params["db"] = $db;
                        }
                        if ($searchQuery != "") {
                            $params["q"] = $searchQuery;
                        }
                        if (strpos($currentUrl, "?") !== false) {
                            $explodedUrl = explode("?", $currentUrl);
                            $currentUrl = $explodedUrl[0];
                        }
                        $totalApi = $counter-1;
                        echo "<label class='total_api_count'>".$totalApi." API(s)</label>";
                        if ($pageNumber == 0) {
                            $pageNumber = 1;
                        }
                        $prev = $pageNumber - 1;
                        $next = $pageNumber + 1;
                        $lastpage = ceil($totalApi/$limitPerPage);
                        $lpm1 = $lastpage - 1;
                        $pagination = "";
                        if ($lastpage > 1) {
                            $pagination .= "<div class='pagination'><ul>";
                            if ($pageNumber > 1) {
                                $params["p"] = $prev;
                                $getdata = "?".http_build_query($params);
                                $pagination .= "<li><a href='$currentUrl".$getdata."'>&laquo</a></li>";
                            } else {
                                $pagination.= "<li class='disabled'><a href='#'>&laquo;</li>";
                            }
                            if ($lastpage < 7 + ($adjacents * 2)) {
                                for ($counter = 1; $counter <= $lastpage; $counter++) {
                                    if ($counter == $pageNumber) {
                                        $pagination.= "<li class='active'><a href='#'>$counter</a></li>";
                                    } else {
                                        $params["p"] = $counter;
                                        $getdata = "?".http_build_query($params);
                                        $pagination.= "<li><a href='$currentUrl".$getdata."'>$counter</a></li>";
                                    }
                                }
                            } else {
                                if ($lastpage > 5 + ($adjacents * 2)) {
                                    if ($pageNumber < 1 + ($adjacents * 2)) {
                                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                                            if ($counter == $pageNumber) {
                                                $pagination.= "<li class='active'><a href='#'>$counter</a></li>";
                                            } else {
                                                $params["p"] = $counter;
                                                $getdata = "?".http_build_query($params);
                                                $pagination .= "<li><a href='$currentUrl".$getdata."'>$counter</a></li>";
                                            }
                                        }
                                        $pagination.= "<li><a href='#'>...</a></li>";
                                        $params["p"] = $lpm1;
                                        $getdata = "?".http_build_query($params);
                                        $pagination.= "<li><a href='$currentUrl?p=$lpm1'>$lpm1</a></li>";
                                        $params["p"] = $lastpage;
                                        $getdata = "?".http_build_query($params);
                                        $pagination.= "<li><a href='$currentUrl?p=$lastpage'>$lastpage</a></li>";
                                    } elseif ($lastpage - ($adjacents * 2) > $pageNumber && $pageNumber > ($adjacents * 2)) {
                                        $params["p"] = 1;
                                        $getdata = "?".http_build_query($params);
                                        $pagination.= "<li><a href='$currentUrl"."$getdata'>1</a></li>";
                                        $params["p"] = 2;
                                        $getdata = "?".http_build_query($params);
                                        $pagination.= "<li><a href='$currentUrl"."$getdata'>2</a></li>";
                                        $pagination.= "<li><a href='#'>...</a></li>";
                                        for ($counter = $pageNumber - $adjacents; $counter <= $pageNumber + $adjacents; $counter++) {
                                            if ($counter == $pageNumber) {
                                                $pagination.= "<li class='active'><a href='#'>$counter</a></li>";
                                            } else {
                                                $params["p"] = $counter;
                                                $getdata = "?".http_build_query($params);
                                                $pagination.= "<li><a href='$currentUrl".$getdata."'>$counter</a></li>";
                                            }
                                        }
                                        $pagination.= "<li><a href='#'>...</a></li>";
                                        $params["p"] = $lpm1;
                                        $getdata = "?".http_build_query($params);
                                        $pagination.= "<li><a href='$currentUrl?p=$lpm1'>$lpm1</a></li>";
                                        $params["p"] = $lastpage;
                                        $getdata = "?".http_build_query($params);
                                        $pagination.= "<li><a href='$currentUrl?p=$lastpage'>$lastpage</a></li>";
                                    } else {
                                        $params["p"] = 1;
                                        $getdata = "?".http_build_query($params);
                                        $pagination.= "<li><a href='$currentUrl"."$getdata'>1</a></li>";
                                        $params["p"] = 2;
                                        $getdata = "?".http_build_query($params);
                                        $pagination.= "<li><a href='$currentUrl"."$getdata'>2</a></li>";
                                        $pagination.= "<li><a href='#'>...</a></li>";
                                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                                            if ($counter == $pageNumber) {
                                                $pagination.= "<li class='active'><a href='#'>$counter</a></li>";
                                            } else {
                                                $params["p"] = $counter;
                                                $getdata = "?".http_build_query($params);
                                                $pagination .= "<li><a href='$currentUrl".$getdata."'>$counter</a></li>";
                                            }
                                        }
                                    }
                                }
                            }
                            if ($pageNumber < $counter - 1) {
                                $params["p"] = $next;
                                $getdata = "?".http_build_query($params);
                                $pagination .= "<li><a href='$currentUrl".$getdata."'>&raquo;</a></li>";
                            } else {
                                $pagination.= "<li class='disabled'><a href='#'>&raquo;</a></li>";
                            }
                            $pagination.= "</ul></div>";
                        }
                        echo $pagination;   ?>
                    </div>
                    <div class="add_api_container hidden">
                        <div class="form_row">
                            <div class="form_row_label_container">
                                <label class="form_row_label">Select Group</label>
                            </div>
                            <div class="form_row_field_container styled-select blue">
                                <select class="form_row_input form_select group_selection">
<?php                           foreach ($apiDocumentInArray["group-list"] as $key => $eachGroup) { ?>
                                    <option value="<?= $key; ?>"><?= $eachGroup["group"]; ?></option>
<?php                           }   ?>
                                    <option value="new">Create New Group</option>
                                </select>
                                <i class="material-icons form_selection_icon">keyboard_arrow_down</i>
                            </div>
                            <div class="form_row_action_container">
                                <input type="checkbox" class="change_group"/>
                                <label>Use Default</label>
                            </div>
                        </div>
                        <div class="form_row new_group_name_container">
                            <div class="form_row_label_container">
                                <label class="form_row_label">Group Name</label>
                            </div>
                            <div class="form_row_field_container">
                                <input class="form_row_input form_text_field new_group_name" type="text"/>
                            </div>
                            <div class="form_row_action_container">
                                <input type="checkbox" class="change_groupname"/>
                                <label>Use Default</label>
                            </div>
                        </div>
                        <div class="form_row new_group_description_container">
                            <div class="form_row_label_container">
                                <label class="form_row_label">Group Description</label>
                            </div>
                            <div class="form_row_field_container">
                                <textarea class="form_row_input form_textarea_field new_group_description"></textarea>
                            </div>
                            <div class="form_row_action_container">
                                <input type="checkbox" class="change_groupdescription"/>
                                <label>Use Default</label>
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form_row_label_container">
                                <label class="form_row_label">Api Name</label>
                            </div>
                            <div class="form_row_field_container">
                                <input class="form_row_input form_text_field api_name" type="text"/>
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form_row_label_container">
                                <label class="form_row_label">Api Description</label>
                            </div>
                            <div class="form_row_field_container">
                                <textarea class="form_row_input form_textarea_field api_description"></textarea>
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form_row_label_container">
                                <label class="form_row_label">Api End-Point</label>
                            </div>
                            <div class="form_row_field_container">
                                <input class="form_row_input form_text_field api_endpoint" type="text"/>
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form_row_label_container">
                                <label class="form_row_label">Select Api Method</label>
                            </div>
                            <div class="form_row_field_container styled-select blue">
                                <select class="form_row_input form_select api_method">
                                    <option value="">Please Select</option>
                                    <option value="PUT">PUT</option>
                                    <option value="GET">GET</option>
                                    <option value="POST">POST</option>
                                    <option value="DELETE">DELETE</option>
                                </select>
                                <i class="material-icons form_selection_icon">keyboard_arrow_down</i>
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form_row_label_container">
                                <label class="form_row_label">Select Api Response Type</label>
                            </div>
                            <div class="form_row_field_container styled-select blue">
                                <select class="form_row_input form_select api_response_type">
                                    <option value="application/json">JSON</option>
                                    <option value="application/xml">XML</option>
                                </select>
                                <i class="material-icons form_selection_icon">keyboard_arrow_down</i>
                            </div>
                        </div>


                        <div class="form_division"></div>
                        <div class="request_parameter_container table_container">
                            <label class="add_param_label">API Request Params</label>
                            <div class="param_table_container">
                                <table class="param_header_table" cellspacing="0" cellpadding="0">
                                    <colgroup>
                                        <col class="required_column">
                                        <col class="name_column">
                                        <col class="datatype_column">
                                        <col class="description_column">
                                        <col class="example_column">
                                        <col class="action_column">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th title="Is Param Required">Req</th>
                                            <th title="Param Name">Name</th>
                                            <th title="Param Data Type">Data Type</th>
                                            <th title="Param Description">Description</th>
                                            <th title="Param Example">Example</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="create_param_pannel">
                                            <td>
                                                <input type="checkbox" class="pannel_param_is_required" title="Is Required"/>
                                            </td>
                                            <td>
                                                <input type="text" class="form_row_input form_text_field param_text_field pannel_param_name" title="Param Name" placeholder="Param Name"/>
                                            </td>
                                            <td>
                                                <div class="styled-select blue param_select_container" title="Param Data Type">
                                                    <select class="form_row_input form_select param_select pannel_param_type">
                                                        <option value="">Please Select</option>
                                                        <option value="file">File</option>
                                                        <option value="float">Float</option>
                                                        <option value="string">String</option>
                                                        <option value="double">Double</option>
                                                        <option value="boolean">Boolean</option>
                                                        <option value="integer">Integer</option>
                                                        <option value="array of object">Array of object</option>
                                                    </select>
                                                    <i class="material-icons form_selection_icon">keyboard_arrow_down</i>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea class="param_textarea pannel_param_decription" title="Param Description" placeholder="Param Description"></textarea>
                                            </td>
                                            <td>
                                                <textarea class="param_textarea pannel_param_example" title="Param Example" placeholder="Param Example in String"></textarea>
                                            </td>
                                            <td>
                                                <button class="has_required_field add_param_button request_param_button">ADD</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form_division"></div>
                        <div class="response_parameter_container table_container">
                            <label class="add_param_label">API Response Params</label>
                            <div class="param_table_container">
                                <table class="param_header_table" cellspacing="0" cellpadding="0">
                                    <colgroup>
                                        <col class="name_column">
                                        <col class="datatype_column">
                                        <col class="description_column">
                                        <col class="example_column">
                                        <col class="action_column">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th title="Param Name">Name</th>
                                            <th title="Param Data Type">Data Type</th>
                                            <th title="Param Description">Description</th>
                                            <th title="Param Example">Example</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="create_param_pannel">
                                            <td>
                                                <input type="text" class="form_row_input form_text_field param_text_field pannel_param_name" title="Param Name" placeholder="Param Name"/>
                                            </td>
                                            <td>
                                                <div class="styled-select blue param_select_container" title="Param Data Type">
                                                    <select class="form_row_input form_select param_select pannel_param_type">
                                                        <option value="">Please Select</option>
                                                        <option value="file">File</option>
                                                        <option value="float">Float</option>
                                                        <option value="string">String</option>
                                                        <option value="double">Double</option>
                                                        <option value="boolean">Boolean</option>
                                                        <option value="integer">Integer</option>
                                                        <option value="array of object">Array of object</option>
                                                    </select>
                                                    <i class="material-icons form_selection_icon">keyboard_arrow_down</i>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea class="param_textarea pannel_param_decription" title="Param Description" placeholder="Param Description"></textarea>
                                            </td>
                                            <td>
                                                <textarea class="param_textarea pannel_param_example" title="Param Example" placeholder="Param Example in String"></textarea>
                                            </td>
                                            <td>
                                                <button class="add_param_button response_param_button">ADD</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form_division"></div>
                        <div class="header_request_parameter_container table_container">
                            <label class="add_param_label">API Request Header Params</label>
                            <div class="param_table_container">
                                <table class="param_header_table" cellspacing="0" cellpadding="0">
                                    <colgroup>
                                        <col class="name_column">
                                        <col class="datatype_column">
                                        <col class="description_column">
                                        <col class="example_column">
                                        <col class="action_column">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th title="Param Name">Name</th>
                                            <th title="Param Data Type">Data Type</th>
                                            <th title="Param Description">Description</th>
                                            <th title="Param Example">Example</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="create_param_pannel">
                                            <td>
                                                <input type="text" class="form_row_input form_text_field param_text_field pannel_param_name" title="Param Name" placeholder="Param Name"/>
                                            </td>
                                            <td>
                                                <div class="styled-select blue param_select_container" title="Param Data Type">
                                                    <select class="form_row_input form_select param_select pannel_param_type">
                                                        <option value="">Please Select</option>
                                                        <option value="file">File</option>
                                                        <option value="float">Float</option>
                                                        <option value="string">String</option>
                                                        <option value="double">Double</option>
                                                        <option value="boolean">Boolean</option>
                                                        <option value="integer">Integer</option>
                                                        <option value="array of object">Array of object</option>
                                                    </select>
                                                    <i class="material-icons form_selection_icon">keyboard_arrow_down</i>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea class="param_textarea pannel_param_decription" title="Param Description" placeholder="Param Description"></textarea>
                                            </td>
                                            <td>
                                                <textarea class="param_textarea pannel_param_example" title="Param Example" placeholder="Param Example in String"></textarea>
                                            </td>
                                            <td>
                                                <button class="add_param_button request_header_button">ADD</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form_division"></div>
                        <div class="header_response_parameter_container table_container">
                            <label class="add_param_label">API Response Header Params</label>
                            <div class="param_table_container">
                                <table class="param_header_table" cellspacing="0" cellpadding="0">
                                    <colgroup>
                                        <col class="name_column">
                                        <col class="datatype_column">
                                        <col class="description_column">
                                        <col class="example_column">
                                        <col class="action_column">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th title="Name">Name</th>
                                            <th title="Data Type">Data Type</th>
                                            <th title="Description">Description</th>
                                            <th title="Example">Example</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="create_param_pannel">
                                            <td>
                                                <input type="text" class="form_row_input form_text_field param_text_field pannel_param_name" title="Param Name" placeholder="Param Name"/>
                                            </td>
                                            <td>
                                                <div class="styled-select blue param_select_container" title="Param Data Type">
                                                    <select class="form_row_input form_select param_select pannel_param_type">
                                                        <option value="">Please Select</option>
                                                        <option value="file">File</option>
                                                        <option value="float">Float</option>
                                                        <option value="string">String</option>
                                                        <option value="double">Double</option>
                                                        <option value="boolean">Boolean</option>
                                                        <option value="integer">Integer</option>
                                                        <option value="array of object">Array of object</option>
                                                    </select>
                                                    <i class="material-icons form_selection_icon">keyboard_arrow_down</i>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea class="param_textarea pannel_param_decription" title="Param Description" placeholder="Param Description"></textarea>
                                            </td>
                                            <td>
                                                <textarea class="param_textarea pannel_param_example" title="Param Example" placeholder="Param Example in String"></textarea>
                                            </td>
                                            <td>
                                                <button class="add_param_button response_header_button">ADD</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form_division"></div>
                        <div class="api_response_example_container">
                            <textarea placeholder="Paste here full response of this api in json or xml" class="api_response_example_textarea"></textarea>
                        </div>
                        <div class="form_division"></div>
                        <div class="create_api_doc_button_container">
                            <button class="create_api_doc_button">ADD</button>
                        </div>
                    </div>


                    <div class="run_add_api_container hidden">
                        <div class="run_api_head_container">
                            <div class="run_api_method_container">
                                <select class="run_api_method">
                                    <option value="GET">GET</option>
                                    <option value="PUT">PUT</option>
                                    <option value="POST">POST</option>
                                    <option value="DELETE">DELETE</option>
                                </select>
                                <i class="material-icons run_api_method_icon">arrow_drop_down</i>
                            </div>
                            <input type="text" class="run_api_url" placeholder="Enter request URL"/>
                        </div>
                        <div class="run_header_container">
                            <label class="run_header_heading">Request Headers</label>
                            <table class="run_header_table">
                                <thead>
                                    <tr>
                                        <th class="small_width"></th>
                                        <th>KEY</th>
                                        <th>VALUE</th>
                                        <th class="small_width"></th>
                                    </tr>
                                </thead>
                                <tbody class="headerTbody">
                                    <tr class="odd">
                                        <td><input type="checkbox" class="run_each_header_check" checked/></td>
                                        <td><input type="text" class="run_each_header_key run_input runHeader" placeholder="Key"/></td>
                                        <td><input type="text" class="run_each_header_value run_input runHeader" placeholder="Value"/></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="run_param_container">
                            <label class="run_param_heading">Request Params</label>
                            <table class="run_param_table">
                                <thead>
                                    <tr>
                                        <th class="small_width"></th>
                                        <th>KEY</th>
                                        <th>VALUE</th>
                                        <th class="small_width"></th>
                                    </tr>
                                </thead>
                                <tbody class="paramTbody">
                                    <tr class="odd">
                                        <td><input type="checkbox" class="run_each_param_check" checked/></td>
                                        <td><input type="text" class="run_each_param_key run_input runParam" placeholder="Key"/></td>
                                        <td><input type="text" class="run_each_param_value run_input runParam" placeholder="Value"/></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="response_summary">
                            <div class="response_one_summary">
                                <span class="response_one_summary_key">Status:</span>
                                <span class="response_one_summary_value status_summary"></span>
                            </div>
                            <div class="response_one_summary">
                                <span class="response_one_summary_key">Time:</span>
                                <span class="response_one_summary_value time_summary"></span>
                            </div>
                            <div class="response_one_summary">
                                <span class="response_one_summary_key">Size:</span>
                                <span class="response_one_summary_value size_summary"></span>
                            </div>
                            <div class="response_one_summary token_block hidden">
                                <span class="response_one_summary_key">Token:</span>
                                <span class="response_one_summary_value token_summary"></span>
                            </div>
                            <button class="run_api_button run_api_send">SEND</button>
                            <button class="run_api_button run_api_add">ADD</button>
                        </div>
                        <div class="run_response_container">
                            <div class="run_response_textarea">
                                <pre></pre>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    var sessionId = "<?= $_SESSION["id"]; ?>";
    var currentUserEmail = "<?= $_SESSION["userName"]; ?>";
</script>
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
}
if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
    $link = "https"; 
} else {
    $link = "http";
}
$link .= "://";
$link .= $_SERVER["HTTP_HOST"]."/";
$link .= explode("/", $_SERVER["REQUEST_URI"])[1];
?>
<script type="text/javascript">
    var currentUrl = "<?= $link; ?>/dashboard";
</script>
<?php

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

?>
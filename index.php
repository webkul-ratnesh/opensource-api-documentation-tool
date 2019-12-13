<?php
// @codingStandardsIgnoreStart
error_reporting(E_ALL);
ini_set("display_errors", 1);
$allDb = glob("db/*.json");
$file = $allDb[0];
if (isset($_GET["db"]) && $_GET["db"] != "") {
    $file = "db/".$_GET["db"].".json";
}
if (is_file($file)) {
    $apiDocumentInArray = json_decode(file_get_contents($file), true);
} else {
    $apiDocumentInArray = [];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <link rel="shortcut icon" type="image/png" href="images/favicon.png"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="css/styles.css?v=1.3" rel="stylesheet"/>
        <link href="css/jquery.json-viewer.css?v=1.3" rel="stylesheet"/>
        <title>Mobikul Api Documenter</title>
        <script type="text/javascript">
            if (typeof jQuery == "undefined") {
                document.write(unescape("%3Cscript src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' type='text/javascript'%3E%3C/script%3E"));
            }
        </script>
        <script type="text/javascript" charset="utf-8" async="" src="js/main.js?v=1.3"></script>
        <script type="text/javascript" charset="utf-8" async="" src="js/jquery.json-viewer.js"></script>
    </head>
    <body>
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
                <div class="api_group_main_container">
                    <label class="api_group_main_label">API GROUP</label>
                    <div class="api_group_container">
<?php                   if (isset($apiDocumentInArray["group-list"])) {
                            foreach ($apiDocumentInArray["group-list"] as $key => $eachGroup) { ?>
                            <div data-index="<?= $key;?>" title="<?= $eachGroup["group"]; ?>" class="api_each_group <?= ($key%2) ? "even" : "odd";?>">
                                <label class="api_group_label"><?= $eachGroup["group"]; ?><span class="api_count">(<?= count($eachGroup["api-list"]); ?>)</span></label>
<?php                           if (!empty($eachGroup["api-list"])) { ?>
                                    <i class="material-icons api_group_action">keyboard_arrow_down</i>
<?php                           }   ?>
                                <div class="api_group_apilist">
<?php                               foreach ($eachGroup["api-list"] as $internalKey => $eachApi) {    ?>
                                        <div data-index="<?= $internalKey;?>" title="<?= $eachApi["api-name"]; ?>" class="api_group_each_api_container <?= ($internalKey%2) ? "even" : "odd";?>">
                                            <label class="api_group_each_apiname">
<?php                                           if (strlen($eachApi["api-name"]) > 35) {
                                                    echo substr($eachApi["api-name"], 0, 32)." ...";
                                                } else {
                                                    echo $eachApi["api-name"];
                                                } ?>
                                            </label>
                                        </div>
<?php                               }   ?>
                                </div>
                            </div>
<?php                       }
                        } else {
                            echo "<div class='invalid_db_msg'>Invalid Database</div>";
                        }   ?>
                    </div>
                </div>
            </div>
            <div id="right_container" class="sibling_container">
                <div class="loader_container">
                    <div class="cp-spinner cp-meter"></div>
                </div>
                <div id="header">
                    <div class="header_sibling_container">
                        <label class="group_label">Catalog</label>
                    </div>
                    <div class="header_sibling_container db_selection_container">
                        <!-- <label class="db_selection_label">Select Database</label> -->
                        <div class="styled-select blue semi-square">
                            <select class="form_selection">
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
                    <div class="header_sibling_container">
                        <div class="search_bar">
                            <input placeholder="Search API" type="text" class="search_input"/>
                            <i class="material-icons search_button">search</i>
                        </div>
                    </div>
                </div>
                <div class="main_content hidden">
                    <label class="group_description"></label>
                    <div class="api_name_conatiner">
                        <label class="api_name_heading"></label>
                    </div>
                    <div class="api_extra_details_container">
                        <table class="api_extra_details_table">
                            <tr>
                                <td class="api_keyword">Description</td>
                                <td class="api_description"></td>
                            </tr>
                            <tr>
                                <td class="api_keyword">End Point</td>
                                <td class="api_endpoint"></td>
                            </tr>
                            <tr>
                                <td class="api_keyword">Method</td>
                                <td  class="api_method"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="toolbar">
                        <label class="toolbar_button collapser">
                            <span class="toolbar_label">Collapse All</span>
                            <i class="material-icons toolbar_icon">remove_circle_outline</i>
                        </label>
                        <label class="toolbar_button expander active">
                            <span class="toolbar_label">Expand All</span>
                            <i class="material-icons toolbar_icon">add_circle_outline</i>
                        </label>
                    </div>
                    <div class="api_request_response_details_container">
                        <label class="api_request_response_main_heading">Request Data</label>
                        <div class="api_request_response_param_container">
                            <label class="api_request_response_param_heading">Params (JSON) </label>
                            <i class="material-icons app_desc_toggle_button">remove_circle_outline</i>
                            <div class="api_request_response_param_table_container">
                                <table class="api_request_response_param_table api_request_param_table">
                                    <colgroup>
                                        <col class="key_column">
                                        <col class="type_column">
                                        <col class="desc_column">
                                        <col class="fixed_width">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>KEY</th>
                                            <th>TYPE</th>
                                            <th>DESCRIPTION</th>
                                            <th>EXAMPLE</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="api_request_response_header_container">
                            <label class="api_request_response_header_heading">Headers</label>
                            <i class="material-icons app_desc_toggle_button">remove_circle_outline</i>
                            <div class="api_request_response_header_table_container">
                                <table class="api_request_response_header_table api_request_header_table">
                                    <colgroup>
                                        <col class="key_column">
                                        <col class="type_column">
                                        <col class="desc_column">
                                        <col class="fixed_width">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>KEY</th>
                                            <th>TYPE</th>
                                            <th>DESCRIPTION</th>
                                            <th>EXAMPLE</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="api_request_response_details_container">
                        <label class="api_request_response_main_heading">Response Data</label>
                        <div class="api_request_response_param_container">
                            <label class="api_request_response_param_heading">Params (JSON) </label>
                            <i class="material-icons app_desc_toggle_button">remove_circle_outline</i>
                            <div class="api_request_response_param_table_container">
                                <table class="api_request_response_param_table api_response_param_table">
                                    <colgroup>
                                        <col class="key_column">
                                        <col class="type_column">
                                        <col class="desc_column">
                                        <col class="fixed_width">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>KEY</th>
                                            <th>TYPE</th>
                                            <th>DESCRIPTION</th>
                                            <th>EXAMPLE</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="api_request_response_header_container">
                            <label class="api_request_response_header_heading">Headers</label>
                            <i class="material-icons app_desc_toggle_button">remove_circle_outline</i>
                            <div class="api_request_response_header_table_container">
                                <table class="api_request_response_header_table api_response_header_table">
                                    <colgroup>
                                        <col class="key_column">
                                        <col class="type_column">
                                        <col class="desc_column">
                                        <col class="fixed_width">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>KEY</th>
                                            <th>TYPE</th>
                                            <th>DESCRIPTION</th>
                                            <th>EXAMPLE</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="api_request_response_details_container">
                        <label class="api_request_response_main_heading">Example</label>
                        <i class="material-icons app_desc_toggle_button example_data_toggle_button">remove_circle_outline</i>
                        <div class="api_request_response_header_table_container example_container">
                            <pre></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
<?php
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
        var currentUrl = "<?= $link; ?>";
    </script>
</html>
var db = $(".form_selection").val();
var changedGroup = "";
var averageTime = 0;
var frequency = 10;
var runnerCounter = 1;
var options = {
    collapsed: false,
    withQuotes: false
};
$(document).ready(function(){
    $(".action_list").find(".action_label").eq(0).addClass("selected_action");
});

$(".form_selection").on("change", function(){
    location.href = currentUrl+"?db="+$(this).val();
});

$(".action_label").on("click", function(){
    $(".action_label").removeClass("selected_action");
    $(this).addClass("selected_action");
    $(".add_api_container, .api_list_conatiner, .run_add_api_container").addClass("hidden");
    if ($(this).attr("data-for") == "api_list") {
        $(".search_bar_container").show();
        $(".group_label").text("API LIST");
        $(".api_list_conatiner").removeClass("hidden");
    } else if ($(this).attr("data-for") == "add_api") {
        $(".search_bar_container").hide();
        $(".group_selection").removeAttr("disabled");
        var apiActionButton = $(".create_api_doc_button");
        apiActionButton.text("ADD");
        apiActionButton.addClass("add_api");
        apiActionButton.removeClass("edit_api");
        apiActionButton.removeAttr("data-apiindex");
        apiActionButton.removeAttr("data-groupindex");
        $(".group_label").text("ADD API");
        $(".add_api_container").removeClass("hidden");
        resetForm();
        $(".loader_container").show();
        $.ajax({
            url: "read",
            type: "POST",
            dataType: "json",
            data: {
                db : db,
                for: "add",
                groupIndex : 0
            },
            success: function(apiData){
                $(".loader_container").hide();
                $(".new_group_name").val(apiData["group-name"]).attr("disabled", "disabled");
                $(".new_group_description").val(apiData["group-description"]).attr("disabled", "disabled");
                $(".change_groupname, .change_groupdescription, .changroup").prop("checked", true);
            },
            error: function(){
                $(".loader_container").hide();
                errorNotification("Sorry something went wrong.");
            }
        });
    } else if ($(this).attr("data-for") == "run_add_api") {
        $(".search_bar_container").hide();
        $(".group_label").text("Run & ADD API");
        $(".run_add_api_container").removeClass("hidden");
        if (typeof localStorage.runmethod != "undefined") {
            $(".run_api_method").val(localStorage.runmethod);
        }
        if (typeof localStorage.runurlEndPoint != "undefined") {
            $(".run_api_url").val(localStorage.runurlEndPoint);
        }
        if (typeof localStorage.runparams != "undefined") {
            var runparams = JSON.parse(localStorage.runparams);
            var counter = 0;
            $.each(runparams, function(index, value){
                $(".paramTbody").find(".run_each_param_key").eq(counter).val(index);
                $(".paramTbody").find(".run_each_param_value").eq(counter).val(value).trigger("keyup");
                counter++;
            });
        }
        if (typeof localStorage.runheaders != "undefined") {
            var runheaders = JSON.parse(localStorage.runheaders);
            var counter = 0;
            $.each(runheaders, function(index, value){
                $(".headerTbody").find(".run_each_header_key").eq(counter).val(index);
                $(".headerTbody").find(".run_each_header_value").eq(counter).val(value).trigger("keyup");
                counter++;
            });
        }
        if (typeof localStorage.runresponse != "undefined") {
            $(".run_response_textarea pre").html(localStorage.runresponse);
            $(".run_response_textarea pre").jsonViewer(JSON.parse(localStorage.runresponse), options);
        }
    }
});

$(".form_row_action_container input").on("change", function(){
    if ($(this).is(":checked")) {
        $(this).parents(".form_row_action_container").prev().find(".form_row_input").attr("disabled", "disabled");
    } else {
        $(this).parents(".form_row_action_container").prev().find(".form_row_input").removeAttr("disabled");
    }
});

function resetForm()
{
    $(".group_selection").val(0);
    $(".api_response_type").val("application/json");
    $(".new_group_description, .new_group_name, .form_text_field, .form_textarea_field, .api_method, .api_response_example_textarea").val("");
    $(".table_container").find(".request_params_containers, .response_params_containers, .request_header_containers, .response_header_containers").remove();
}

$(".group_selection").on("change", function(){
    var thisGroupIndex = $(this).val();
    if (thisGroupIndex == "new") {
        $(".form_row_action_container input").prop("checked", false).trigger("change");
        $(".new_group_name, .new_group_description").val("");
    } else {
        if (changedGroup != thisGroupIndex) {
            // changedGroup = thisGroupIndex;
        } else {
            changedGroup = "";
        }
        $(".loader_container").show();
        $.ajax({
            url: "read",
            type: "POST",
            dataType: "json",
            data: {
                db : db,
                for: "edit",
                groupIndex : thisGroupIndex
            },
            success: function(apiData){
                $(".loader_container").hide();
                $(".new_group_name").removeAttr("disabled").val(apiData["group-name"]).attr("disabled", "disabled");
                $(".new_group_description").removeAttr("disabled").val(apiData["group-description"]).attr("disabled", "disabled");
                $(".create_api_doc_button").attr("data-groupindex", thisGroupIndex);
            },
            error: function(){
                $(".loader_container").hide();
                errorNotification("Sorry something went wrong.");
            }
        });
    }
});

$(".logout_button_container").on("click", function(){
    window.location.assign("logout")
});

$(".accinfo_button_container").on("click", function(){
    $(".accinfo_modalbox_overlay").fadeIn();
    $(".accinfo_modalbox").animate({"top":"50%"});
});

$(document).on("click", ".accinfo_modalbox_overlay", function(e){
    if (e.target === this) {
        $(".accinfo_modalbox").animate({"top":"-50%"}, () => {
            $(".accinfo_modalbox_overlay").fadeOut();
        });
    }
});

$(document).on("click", ".accinfo_modalbox_footer_button", function(){
    var error = false, errormsg = "", accinfoName = $("#accinfo_name"), accinfoEmail = $("#accinfo_email"), accinfoPassword = $("#accinfo_password"), accinfoConfirmpassword = $("#accinfo_confirmpassword");
    var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    $(".accinfo_modalbox").find(".input_error").removeClass("input_error");
    if (accinfoName.val() == "") {
        error = true;
        errormsg = "User Name is required field.";
        accinfoName.addClass("input_error");
        $("html, body").animate({
            scrollTop: accinfoName.offset().top
        }, 300);
    } else if (accinfoEmail.val() == "") {
        error = true;
        errormsg = "User Email is required field.";
        accinfoEmail.addClass("input_error");
        $("html, body").animate({
            scrollTop: accinfoEmail.offset().top
        }, 300);
    } else if (!emailRegex.test(accinfoEmail.val().trim())) {
        error = true;
        errormsg = "Please provide valid email.";
        accinfoEmail.addClass("input_error");
        $("html, body").animate({
            scrollTop: accinfoEmail.offset().top
        }, 300);
    }
    var password = "";
    if (accinfoPassword.val() != "" || accinfoConfirmpassword.val() != "") {
        if (accinfoPassword.val() == "") {
            error = true;
            errormsg = "Password is required field.";
            accinfoPassword.addClass("input_error");
            $("html, body").animate({
                scrollTop: accinfoPassword.offset().top
            }, 300);
        } else if (accinfoConfirmpassword.val() == "") {
            error = true;
            errormsg = "Confirm Password is required field.";
            accinfoConfirmpassword.addClass("input_error");
            $("html, body").animate({
                scrollTop: accinfoConfirmpassword.offset().top
            }, 300);
        } else if (accinfoConfirmpassword.val() != accinfoPassword.val()) {
            error = true;
            errormsg = "Password & Confirm Password should be same.";
            accinfoConfirmpassword.addClass("input_error");
            $("html, body").animate({
                scrollTop: accinfoConfirmpassword.offset().top
            }, 300);
        } else {
            password = accinfoPassword.val();
        }
    }

    if (!error) {
        $(".loader_container").show();
        $.ajax({
            url: "update",
            type: "POST",
            dataType: "json",
            data: {
                sessionId: sessionId,
                userName: accinfoName.val(),
                userEmail: accinfoEmail.val(),
                userPassword: password
            },
            success: function(apiData){
                $(".loader_container").hide();
                successNotification(apiData["message"]);
                setTimeout(function(){
                    location.reload();
                }, 2000);
            },
            error: function(){
                $(".loader_container").hide();
                errorNotification("Sorry something went wrong.");
            }
        });
    } else {
        errorNotification(errormsg);
    }
});

$(".add_param_button").on("click", function(){
    $(this).parents(".param_table_container").find(".warning_message").remove();
    var error = false,
        paramName = "",
        paramType = "",
        paramExample = "",
        paramDescription = "",
        isParamRequired = false,
        paramNameField = $(this).parents(".param_table_container").find(".pannel_param_name"),
        paramTypeField = $(this).parents(".param_table_container").find(".pannel_param_type"),
        paramExampleField = $(this).parents(".param_table_container").find(".pannel_param_example"),
        paramDescriptionField = $(this).parents(".param_table_container").find(".pannel_param_decription");
    if ($(".pannel_param_is_required").prop("checked") == false) {
        isParamRequired = false;
    } else {
        isParamRequired = true;
    }
    if (paramNameField.val() == "") {
        error = true;
        paramNameField.after("<span class='warning_message'>Name is required field.</span>");
    } else {
        paramName = paramNameField.val();
    }
    if (paramTypeField.val() == "") {
        error = true;
        paramTypeField.parents(".param_select_container").after("<span class='warning_message'>Type is required field.</span>");
    } else {
        paramType = paramTypeField.val();
    }
    if (paramDescriptionField.val() == "") {
        error = true;
        paramDescriptionField.after("<span class='warning_message'>Description is required field.</span>");
    } else {
        paramDescription = paramDescriptionField.val();
    }
    if (paramExampleField.val() == "") {
        error = true;
        paramExampleField.after("<span class='warning_message'>Example is required field.</span>");
    } else {
        paramExample = paramExampleField.val();
    }
    if (!error) {
        var paramHtml = "<tr class='";
        if ($(this).hasClass("request_param_button")) {
            paramHtml += "request_params_containers'";
        }
        if ($(this).hasClass("response_param_button")) {
            paramHtml += "response_params_containers'";
        }
        if ($(this).hasClass("request_header_button")) {
            paramHtml += "request_header_containers'";
        }
        if ($(this).hasClass("response_header_button")) {
            paramHtml += "response_header_containers'";
        }
        paramHtml += ">";
        if ($(this).hasClass("has_required_field")) {
            paramHtml += "<td>";
            if (isParamRequired) {
                paramHtml += "<i class='material-icons required required_icon'>grade</i>";
            } else {
                paramHtml += "<i class='material-icons not-required required_icon'>grade</i>";
            }
            paramHtml += "</td>";
        }
        paramHtml += "<td><label class='each_param_name'>"+paramName+"</label></td><td><label class='each_param_type'>"+paramType+"</label></td><td><label class='each_param_description'>"+paramDescription+"</label></td><td><label class='each_param_example'><pre>"+paramExample+"</pre></label></td><td><i title='Remove' class='material-icons each_remove_param'>delete_forever</i><i class='material-icons each_edit_param' title='Edit'>edit</i></td></tr>";
        $(this).parents(".create_param_pannel").after(paramHtml);

        $(".pannel_param_is_required").prop("checked", false);
        paramNameField.val("");
        paramTypeField.val("");
        paramExampleField.val("");
        paramDescriptionField.val("");
    }
});

$(document).on("click", ".each_remove_param", function(){
    if (confirm("Are You Sure !!") == true) {
        $(this).parents("tr").remove();
    }
});

$(document).on("click", ".each_edit_param", function(){
    var alreadyEditingCheckTarget = $(this).parents("tbody").find("tr").eq(0);
    if (alreadyEditingCheckTarget.find(".pannel_param_name").val() != "" || alreadyEditingCheckTarget.find(".pannel_param_type").val() != "" || alreadyEditingCheckTarget.find(".pannel_param_decription").val() != "" || alreadyEditingCheckTarget.find(".pannel_param_example").val() != "") {
        errorNotification("Param Update Already in process, please complete update of that param first.");
        return false;
    }
    if (confirm("Are You Sure !!") == true) {
        var parentTr = $(this).parents("tr");
        var target = $(this).parents("tbody").find("tr").eq(0);
        if (parentTr.find(".required_icon").hasClass("not-required")) {
            target.find(".pannel_param_is_required").prop("checked", false);
        } else {
            target.find(".pannel_param_is_required").prop("checked", true);
        }
        target.find(".pannel_param_name").val(parentTr.find(".each_param_name").text());
        target.find(".pannel_param_type").val(parentTr.find(".each_param_type").text());
        target.find(".pannel_param_decription").val(parentTr.find(".each_param_description").text());
        target.find(".pannel_param_example").val(parentTr.find(".each_param_example").find("pre").text());
        parentTr.remove();
    }
});

function isJson(str)
{
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

$(document).on("click", ".api_edit", function(){
    if (confirm("Are You Sure To Edit!!") == true) {
        changedGroup = "";
        $(".loader_container").show();
        $(".group_selection").attr("disabled", "disabled");
        var groupIndex,
            apiIndex,
            thisThis;
        thisThis = $(this).parents("tr");
        groupIndex = thisThis.attr("data-groupindex");
        apiIndex = thisThis.attr("data-apiindex");
        $.ajax({
            url: "read",
            type: "POST",
            dataType: "json",
            data: {
                db : db,
                for: "edit",
                apiIndex : apiIndex,
                groupIndex : groupIndex
            },
            success: function(apiData){
                $(".loader_container").hide();
                $(".action_label").removeClass("selected_action");
                $(".action_list").find(".action_label").eq(1).addClass("selected_action");
                $(".api_list_conatiner").addClass("hidden");
                $(".add_api_container").removeClass("hidden");
                var apiActionButton = $(".create_api_doc_button");
                apiActionButton.text("UPDATE");
                apiActionButton.addClass("edit_api");
                apiActionButton.removeClass("add_api");
                apiActionButton.attr("data-apiindex", thisThis.attr("data-apiindex"));
                apiActionButton.attr("data-groupindex", thisThis.attr("data-groupindex"));
                $(".group_selection").val(groupIndex);
                changedGroup = groupIndex;
                $(".api_name").val(apiData["api-name"]);
                $(".api_method").val(apiData["api-method"]);

                $(".new_group_name").removeAttr("disabled").val(apiData["group-name"]).attr("disabled", "disabled");
                $(".new_group_description").removeAttr("disabled").val(apiData["group-description"]).attr("disabled", "disabled");
                $(".change_groupdescription, .change_groupname, .change_group").prop("checked", true);

                $(".api_endpoint").val(apiData["api-endpoint"]);
                $(".api_description").val(apiData["api-description"]);
                if (typeof apiData["api-response-type"] != "undefined") {
                    $(".api_response_type").val(apiData["api-response-type"]);
                }
                var paramHtml = "";
                for (var i=0; i<apiData["api-params"].length; i++) {
                    paramHtml += "<tr class='request_params_containers'><td>";
                    if (typeof apiData["api-params"][i]["required"] != "undefined" && apiData["api-params"][i]["required"]) {
                        paramHtml += "<i class='material-icons required required_icon'>grade</i>";
                    } else {
                        paramHtml += "<i class='material-icons not-required required_icon'>grade</i>";
                    }
                    paramHtml += "</td><td><label class='each_param_name'>"+apiData["api-params"][i]["key-name"]+"</label></td><td><label class='each_param_type'>"+apiData["api-params"][i]["data-type"]+"</label></td><td><label class='each_param_description'>"+apiData["api-params"][i]["description"]+"</label></td><td><label class='each_param_example'><pre>"+escapeHtml(apiData["api-params"][i]["possible-values"])+"</pre></label></td><td><i title='Remove' class='material-icons each_remove_param'>delete_forever</i><i class='material-icons each_edit_param' title='Edit'>edit</i></td></tr>";
                }
                $(".request_parameter_container").find("tbody").append(paramHtml);
                var paramHtml = "";
                for (var i=0; i<apiData["api-response"].length; i++) {
                    paramHtml += "<tr class='response_params_containers'><td><label class='each_param_name'>"+apiData["api-response"][i]["key-name"]+"</label></td><td><label class='each_param_type'>"+apiData["api-response"][i]["data-type"]+"</label></td><td><label class='each_param_description'>"+apiData["api-response"][i]["description"]+"</label></td><td><label class='each_param_example'><pre>"+apiData["api-response"][i]["possible-values"]+"</pre></label></td><td><i title='Remove' class='material-icons each_remove_param'>delete_forever</i><i class='material-icons each_edit_param' title='Edit'>edit</i></td></tr>";
                }
                $(".response_parameter_container").find("tbody").append(paramHtml);
                var paramHtml = "";
                for (var i=0; i<apiData["request-header-params"].length; i++) {
                    paramHtml += "<tr class='request_header_containers'><td><label class='each_param_name'>"+apiData["request-header-params"][i]["key-name"]+"</label></td><td><label class='each_param_type'>"+apiData["request-header-params"][i]["data-type"]+"</label></td><td><label class='each_param_description'>"+apiData["request-header-params"][i]["description"]+"</label></td><td><label class='each_param_example'><pre>"+apiData["request-header-params"][i]["possible-values"]+"</pre></label></td><td><i title='Remove' class='material-icons each_remove_param'>delete_forever</i><i class='material-icons each_edit_param' title='Edit'>edit</i></td></tr>";
                }
                $(".header_request_parameter_container").find("tbody").append(paramHtml);
                var paramHtml = "";
                for (var i=0; i<apiData["response-header-params"].length; i++) {
                    paramHtml += "<tr class='response_header_containers'><td><label class='each_param_name'>"+apiData["response-header-params"][i]["key-name"]+"</label></td><td><label class='each_param_type'>"+apiData["response-header-params"][i]["data-type"]+"</label></td><td><label class='each_param_description'>"+apiData["response-header-params"][i]["description"]+"</label></td><td><label class='each_param_example'><pre>"+apiData["response-header-params"][i]["possible-values"]+"</pre></label></td><td><i title='Remove' class='material-icons each_remove_param'>delete_forever</i><i class='material-icons each_edit_param' title='Edit'>edit</i></td></tr>";
                }
                $(".header_response_parameter_container").find("tbody").append(paramHtml);
                $(".api_response_example_textarea").val(JSON.stringify(apiData["possible-values"]));
            },
            error: function() {
                $(".loader_container").hide();
            }
        });
    }
});

$(document).on("click", ".api_run", function() {
    var groupIndex,
        apiIndex,
        thisThis;
    thisThis = $(this).parents("tr");
    groupIndex = thisThis.attr("data-groupindex");
    apiIndex = thisThis.attr("data-apiindex");
    $.ajax({
        url: "read",
        type: "POST",
        dataType: "json",
        data: {
            db : db,
            for: "run",
            apiIndex : apiIndex,
            groupIndex : groupIndex
        },
        success: function(apiData){
            $(".loader_container").hide();
            $(".action_list").find(".action_label").eq(2).trigger("click");
            $(".run_api_url").val(apiData["domain"]+apiData["api-endpoint"]);
            $(".run_api_method").val(apiData["api-method"]);
            for (let index = ($(".paramTbody tr").length - 1); index > 0; index--) {
                $(".paramTbody tr").eq(index).remove();
            }
            for (let index = ($(".headerTbody tr").length - 1); index > 0; index--) {
                $(".headerTbody tr").eq(index).remove();
            }
            var counter = 0;
            $.each(apiData["api-params"], function(index, value){
                $(".paramTbody").find(".run_each_param_key").eq(counter).val(value["key-name"]);
                $(".paramTbody").find(".run_each_param_value").eq(counter).val(value["possible-values"]).trigger("keyup");
                counter++;
            });
            var counter = 0;
            $.each(apiData["request-header-params"], function(index, value){
                $(".headerTbody").find(".run_each_header_key").eq(counter).val(value["key-name"]);
                if (value["key-name"] !== "authKey") {
                    $(".headerTbody").find(".run_each_header_value").eq(counter).val(value["possible-values"]).trigger("keyup");
                } else {
                    $(".headerTbody").find(".run_each_header_value").eq(counter).val(localStorage.authKey).trigger("keyup");
                }
                counter++;
            });
            $(".run_api_send").trigger("click");
        },
        error: function() {
            $(".loader_container").hide();
        }
    });
});

function escapeHtml(string)
{
    // string = string.replace(/</g, "&lt;");
    // string = string.replace(/>/g, "&gt;");
    // string = string.replace(/&/g, "&amp;");
    // string = string.replace(/"/g, "&quot;");
    // string = string.replace(/'/g, "&#39;");
    // string = string.replace(/\//g, "&#x2F;");
    return string;
}

$(document).on("click", ".api_delete", function() {
    errorNotification("This functionality is disabled for now.");
    // if (confirm("Are You Sure To Delete!!") == true) {
        // $(this).parents("tr").remove();
    // }
});

$(document).on("click", ".create_api_doc_button", function(){
    $("#main_container").find(".warning_message").remove();
    var error = false, thisThis = $(this), tempJson = {}, groupName = $(".group_selection").val(), errormsg = "";
    var groupIndex, apiIndex = "";
    $(".add_api_container").find(".input_error").removeClass("input_error");
    if (!$(".change_group").is(":checked")) {
        tempJson["group"] = $(".new_group_name").val();
    }
    if (!$(".change_groupdescription").is(":checked")) {
        tempJson["description"] = $(".new_group_description").val();
    }
    groupIndex = groupName;
    var oneApi = {};
    var requestParams = [];
    var requestHeaders = [];
    var responseParams = [];
    var responseHeaders = [];
    oneApi["api-name"] = $(".api_name").val();
    oneApi["api-method"] = $(".api_method").val();
    oneApi["api-endpoint"] = $(".api_endpoint").val();
    oneApi["api-description"] = $(".api_description").val();
    oneApi["api-response-type"] = $(".api_response_type").val();
    if (oneApi["api-name"] == "") {
        error = true;
        errormsg = "Api Name can't be blank.";
        $(".api_name").addClass("input_error");
        $("html, body").animate({
            scrollTop: $(".api_name").offset().top
        }, 300);
    } else if (oneApi["api-method"] == "") {
        error = true;
        errormsg = "Api Method Name can't be blank.";
        $(".api_method").addClass("input_error");
        $("html, body").animate({
            scrollTop: $(".api_method").offset().top
        }, 300);
    } else if (oneApi["api-endpoint"] == "") {
        error = true;
        errormsg = "Api End-Point can't be blank.";
        $(".api_endpoint").addClass("input_error");
        $("html, body").animate({
            scrollTop: $(".api_endpoint").offset().top
        }, 300);
    } else if (oneApi["api-description"] == "") {
        error = true;
        errormsg = "Api Description can't be blank.";
        $(".api_description").addClass("input_error");
        $("html, body").animate({
            scrollTop: $(".api_description").offset().top
        }, 300);
    }
    $(".param_header_table").find(".request_params_containers").each(function(){
        var eachParam = {};
        if ($(this).find(".required_icon").hasClass("required")) {
            eachParam["required"] = true;
        } else {
            eachParam["required"] = false;
        }
        eachParam["key-name"] = $(this).find(".each_param_name").text().trim();
        eachParam["data-type"] = $(this).find(".each_param_type").text().trim();
        eachParam["description"] = $(this).find(".each_param_description").text().trim();
        eachParam["possible-values"] = $(this).find(".each_param_example pre").text().trim();
        requestParams.push(eachParam);
    });

    $(".param_header_table").find(".response_params_containers").each(function(){
        var eachParam = {};
        eachParam["key-name"] = $(this).find(".each_param_name").text().trim();
        eachParam["data-type"] = $(this).find(".each_param_type").text().trim();
        eachParam["description"] = $(this).find(".each_param_description").text().trim();
        eachParam["possible-values"] = $(this).find(".each_param_example pre").text().trim();
        responseParams.push(eachParam);
    });

    $(".param_header_table").find(".request_header_containers").each(function(){
        var eachParam = {};
        eachParam["key-name"] = $(this).find(".each_param_name").text().trim();
        eachParam["data-type"] = $(this).find(".each_param_type").text().trim();
        eachParam["description"] = $(this).find(".each_param_description").text().trim();
        eachParam["possible-values"] = $(this).find(".each_param_example pre").text().trim();
        requestHeaders.push(eachParam);
    });

    $(".param_header_table").find(".response_header_containers").each(function(){
        var eachParam = {};
        eachParam["key-name"] = $(this).find(".each_param_name").text().trim();
        eachParam["data-type"] = $(this).find(".each_param_type").text().trim();
        eachParam["description"] = $(this).find(".each_param_description").text().trim();
        eachParam["possible-values"] = $(this).find(".each_param_example pre").text().trim();
        responseHeaders.push(eachParam);
    });
    oneApi["api-params"] = requestParams;
    oneApi["api-response"] = responseParams;
    oneApi["request-header-params"] = requestHeaders;
    oneApi["response-header-params"] = responseHeaders;
    var example = $(".api_response_example_textarea").val();
    if (!isJson(example)) {
        error = true;
        $(".api_response_example_textarea").after("<span class='warning_message'>Not a valid Json.</span>");
    } else {
        oneApi["possible-values"] = example;
    }
    tempJson["api-list"] = [];
    tempJson["api-list"].push(oneApi);
    var action = "new";
    if (thisThis.hasClass("edit_api")) {
        action = "edit";
    }
    var apiActionButton = $(".create_api_doc_button");
    if (typeof apiActionButton.attr("data-apiindex") != "undefined") {
        apiIndex = apiActionButton.attr("data-apiindex");
    }
    if (!error) {
        localStorage.setItem("data", JSON.stringify(tempJson));
        $(".loader_container").show();
        $.ajax({
            url: "write",
            type: "POST",
            dataType: "json",
            data: {
                db : db,
                action: action,
                apiIndex : apiIndex,
                groupIndex: groupIndex,
                changedGroup : changedGroup,
                data : JSON.stringify(tempJson),
                currentUserEmail : currentUserEmail
            },
            success: function(returnData){
                $(".loader_container").hide();
                if (returnData["success"]) {
                    changedGroup = "";
                    successNotification(returnData["message"]);
                    resetForm();
                    $(".action_list").find(".action_label").eq(0).trigger("click");
                    $(document).scrollTop(0);
                } else {
                    errorNotification(returnData["message"]);
                }
            },
            error: function(){
                $(".loader_container").hide();
                errorNotification("Sorry something went wrong.");
            }
        });
    } else {
        if (errormsg != "")  {
            errorNotification(errormsg);
        }
    }
});

$(document).on("click", ".modalbox_footer_button", function(){
    $(".modalbox").animate({"top":"-50%"}, function(){
        $(".modalbox_overlay").fadeOut();
    });
});

function errorNotification(content)
{
    $(".modalbox_message_indicator").text("error_outline");
    $(".modalbox_content").text(content);
    $(".modalbox_title").text("Error Message");
    $(".modalbox_overlay").fadeIn();
    $(".modalbox").animate({"top":"50%"});
    $(".modalbox_message_indicator").removeClass("success_title").addClass("error_title");
}

function successNotification(content)
{
    $(".modalbox_message_indicator").text("check_circle_outline");
    $(".modalbox_content").html(content);
    $(".modalbox_title").text("Success Message");
    $(".modalbox_overlay").fadeIn();
    $(".modalbox").animate({"top":"50%"});
    $(".modalbox_message_indicator").removeClass("error_title").addClass("success_title");
}

$(".search_input").on("keyup", function(e) {
    if (e.which == 13) {
        var thisQuery = $(this).val().trim();
        var mainUrl = window.location.href.split("?");
        mainUrl = mainUrl[0];
        var url = new URL(window.location.href);
        var params = {};
        var db = url.searchParams.get("db");
        if (db) {
            params["db"] = db;
        }
        if (thisQuery) {
            params["p"] = 1;
            params["q"] = thisQuery;
        }
        params = $.param(params);
        window.location = mainUrl+"?"+params;
    }
});

$(".run_api_method_icon").on("click", function(){
    $(".run_api_method").trigger("click");
});

$("body").on("keyup", ".run_input", function(){
    var thisThis = $(this);
    if (thisThis.val() != "") {
        thisThis.parents("tr").find("td").eq(3).html("<i class='material-icons run_each_delete'>close</i>");
        if (thisThis.hasClass("runHeader")) {
            thisThis.parents("tr").find("td").eq(0).html("<input type='checkbox' class='run_each_header_check' checked/>");
            if (thisThis.parents("tr").index() == (thisThis.parents("tbody").find("tr").length - 1)) {
                var html = "<tr class='even'>";
                if (thisThis.parents("tr").index()%2 != 0) {
                    html = "<tr class='odd'>";
                }
                html += "<td></td>"+
                    "<td><input type='text' class='run_each_header_key run_input runHeader' placeholder='Key'/></td>"+
                    "<td><input type='text' class='run_each_header_value run_input runHeader' placeholder='Value'/></td>"+
                    "<td></td>"+
                "</tr>";
                $(".headerTbody").append(html);
            }
        }
        if (thisThis.hasClass("runParam")) {
            thisThis.parents("tr").find("td").eq(0).html("<input type='checkbox' class='run_each_param_check' checked/>");
            if (thisThis.parents("tr").index() == (thisThis.parents("tbody").find("tr").length - 1)) {
                var html = "<tr class='even'>";
                if (thisThis.parents("tr").index()%2 != 0) {
                    html = "<tr class='odd'>";
                }
                html += "<td></td>"+
                    "<td><input type='text' class='run_each_param_key run_input runParam' placeholder='Key'/></td>"+
                    "<td><input type='text' class='run_each_param_value run_input runParam' placeholder='Value'/></td>"+
                    "<td></td>"+
                "</tr>";
                $(".paramTbody").append(html);
            }
        }
    }
});

$("body").on("click", ".run_each_delete", function(){
    if (confirm("Are you sure!")) {
        $(this).parents("tr").remove();
    }
});

$(".run_api_send").on("click", function(){
    $(".headerTbody, .paramTbody").find(".input_error").removeClass("input_error");
    var params={} ,headers={}, hasError=false, urlEndPoint="", method="";
    $(".headerTbody").find("tr").each(function() {
        var headerThis = $(this);
        var thisHasError = false;
        if (headerThis.find(".run_each_header_check").prop("checked")) {
            var headerKeyInput = headerThis.find(".run_each_header_key");
            var headerKey = headerKeyInput.val();
            var headerValueInput = headerThis.find(".run_each_header_value");
            var headerValue = headerValueInput.val();
            if (headerKey == "") {
                hasError = thisHasError = true;
                headerKeyInput.addClass("input_error");
            }
            if (headerValue == "") {
                hasError = thisHasError = true;
                headerValueInput.addClass("input_error");
            }
            if (!thisHasError) {
                if (headerKey == "authKey") {
                    localStorage.authKey = headerValue;
                }
                headers[headerKey] = headerValue;
            }
        }
    });
    $(".paramTbody").find("tr").each(function() {
        var paramThis = $(this);
        var thisHasError = false;
        if (paramThis.find(".run_each_param_check").prop("checked")) {
            var paramKeyInput = paramThis.find(".run_each_param_key");
            var paramKey = paramKeyInput.val();
            var paramValueInput = paramThis.find(".run_each_param_value");
            var paramValue = paramValueInput.val();
            if (paramKey == "") {
                hasError = thisHasError = true;
                paramKeyInput.addClass("input_error");
            }
            if (paramValue == "") {
                hasError = thisHasError = true;
                paramValueInput.addClass("input_error");
            }
            if (!thisHasError) {
                params[paramKey] = paramValue;
            }
        }
    });
    var urlTarget = $(".run_api_url");
    urlEndPoint = urlTarget.val();
    urlTarget.removeClass("input_error");
    if (urlEndPoint == "") {
        urlTarget.addClass("input_error");
        hasError = true;
    }
    method = $(".run_api_method").val();
    if (!hasError) {
        localStorage.runmethod = method;
        localStorage.runparams = JSON.stringify(params);
        localStorage.runheaders = JSON.stringify(headers);
        localStorage.runurlEndPoint = urlEndPoint;
        $(".loader_container").show();
        if ($("#enable_profiling").prop("checked")) {
            var tmpFrequency = $("#profiling_frequency").val().trim();
            if (tmpFrequency != "" && tmpFrequency > 1) {
                frequency = tmpFrequency;
            } else {
                frequency = 10;
            }
            averageTime = 0;
            runnerCounter = 1;
            profiling(method, params, headers, urlEndPoint);
        } else {
            $.ajax({
                url: "run",
                type: "POST",
                dataType: "json",
                data: {
                    method: method,
                    params: params,
                    headers: headers,
                    urlEndPoint: urlEndPoint
                },
                success: function(apiData){
                    $(".loader_container").hide();
                    if (apiData["responseCode"] == 200) {
                        $(".token_block").addClass("hidden");
                        $(".status_summary").text("200 OK");
                        localStorage.runresponse = JSON.stringify(apiData["data"]);
                        $(".run_response_textarea").html("<pre>"+JSON.stringify(apiData["data"])+"</pre>");
                        $(".run_response_textarea pre").jsonViewer(JSON.parse($(".run_response_textarea pre").text()), options);
                    } else if (apiData["responseCode"] == 401) {
                        $(".token_block").removeClass("hidden");
                        $(".status_summary").text("401 Unauthorized");
                        $(".token_summary").text(apiData["token"]);
                        localStorage.runresponse = JSON.stringify(apiData["data"]);
                        $(".run_response_textarea").html("<pre>"+JSON.stringify(apiData["data"])+"</pre>");
                        $(".run_response_textarea pre").jsonViewer(JSON.parse($(".run_response_textarea pre").text()), options);
                    } else {
                        $(".status_summary").text(apiData["responseCode"]);
                        $(".run_response_textarea").html(apiData["data"]);
                    }
                    $(".time_summary").text(apiData["timetaken"]+" ms");
                    var size = (apiData["size"]/1024);
                    if (size < 1) {
                        $(".size_summary").text(apiData["size"]+" B");
                    } else {
                        $(".size_summary").text(size.toFixed(2)+" KB");
                    }
                },
                error: function(){
                    $(".loader_container").hide();
                }
            });
        }
    }
});

function profiling (
    method,
    params,
    headers,
    urlEndPoint
) {
    $.ajax({
        url: "run",
        type: "POST",
        dataType: "json",
        data: {
            method: method,
            params: params,
            headers: headers,
            urlEndPoint: urlEndPoint
        },
        success: function(apiData){
            if (apiData["responseCode"] == 200) {
                runnerCounter ++;
                averageTime += apiData["timetaken"];
                if (runnerCounter == frequency) {
                    successNotification("Average time taken is <b>"+(averageTime/frequency).toFixed(2)+" ms</b> for <b>"+frequency+"</b> iteration(s).");
                    $(".loader_container").hide();
                } else {
                    profiling(method, params, headers, urlEndPoint);
                }
            } else if (apiData["responseCode"] == 401) {
                $(".token_block").removeClass("hidden");
                $(".status_summary").text("401 Unauthorized");
                $(".token_summary").text(apiData["token"]);
                localStorage.runresponse = JSON.stringify(apiData["data"]);
                $(".run_response_textarea").html("<pre>"+JSON.stringify(apiData["data"])+"</pre>");
                $(".run_response_textarea pre").jsonViewer(JSON.parse($(".run_response_textarea pre").text()), options);
            } else {
                $(".status_summary").text(apiData["responseCode"]);
                $(".run_response_textarea").html(apiData["data"]);
            }
            $(".time_summary").text(apiData["timetaken"]+" ms");
            var size = (apiData["size"]/1024);
            if (size < 1) {
                $(".size_summary").text(apiData["size"]+" B");
            } else {
                $(".size_summary").text(size.toFixed(2)+" KB");
            }
        },
        error: function(){
            $(".loader_container").hide();
        }
    });
}

$(".run_api_add").on("click", function(){
    $(".loader_container").show();
    $(".action_label").eq(1).trigger("click");

    $(".api_response_example_textarea").val(localStorage.runresponse);
    $(".api_method").val(localStorage.runmethod);
    var endPointArr = $(".run_api_url").val().split("/");
    $(".api_endpoint").val(endPointArr[endPointArr.length-3]+"/"+endPointArr[endPointArr.length-2]+"/"+endPointArr[endPointArr.length-1]);

    $(".headerTbody").find("tr").each(function() {
        var headerThis = $(this);
        if (headerThis.find(".run_each_header_check").prop("checked")) {
            var headerKeyInput = headerThis.find(".run_each_header_key");
            var headerKey = headerKeyInput.val();
            var headerValueInput = headerThis.find(".run_each_header_value");
            var headerValue = headerValueInput.val();
            var headerPannel = $(".header_request_parameter_container tbody tr").eq(0);
            headerPannel.find(".pannel_param_name").val(headerKey);
            headerPannel.find(".pannel_param_example,.pannel_param_decription").val(headerValue);
            if (typeof headerValue == "string") {
                headerPannel.find(".pannel_param_type").val("string");
            } else if (typeof paramValue == "boolean") {
                requestPannel.find(".pannel_param_type").val("boolean");
            }
            headerPannel.find(".request_header_button").trigger("click");
        }
    });
    $(".paramTbody").find("tr").each(function() {
        var paramThis = $(this);
        if (paramThis.find(".run_each_param_check").prop("checked")) {
            var paramKeyInput = paramThis.find(".run_each_param_key");
            var paramKey = paramKeyInput.val();
            var paramValueInput = paramThis.find(".run_each_param_value");
            var paramValue = paramValueInput.val();
            var requestPannel = $(".request_parameter_container tbody tr").eq(0);
            requestPannel.find(".pannel_param_name").val(paramKey);
            requestPannel.find(".pannel_param_example, .pannel_param_decription").val(paramValue);
            if (typeof paramValue == "string") {
                requestPannel.find(".pannel_param_type").val("string");
            } else if (typeof paramValue == "boolean") {
                requestPannel.find(".pannel_param_type").val("boolean");
            }
            requestPannel.find(".request_param_button").trigger("click");
        }
    });
    var response = JSON.parse(localStorage.runresponse);
    if (Object.keys(response).length) {
        Object.keys(response).forEach(key => {
            var responsePannel = $(".response_parameter_container tbody tr").eq(0);
            responsePannel.find(".pannel_param_name").val(key);
            if (typeof response[key] == "object") {
                responsePannel.find(".pannel_param_example, .pannel_param_decription").val(JSON.stringify(response[key]));
            } else {
                responsePannel.find(".pannel_param_example, .pannel_param_decription").val(response[key]);
            }
            if (typeof response[key] == "string") {
                responsePannel.find(".pannel_param_type").val("string");
            } else if (typeof response[key] == "object") {
                responsePannel.find(".pannel_param_type").val("array of object");
            } else if (typeof response[key] == "boolean") {
                responsePannel.find(".pannel_param_type").val("boolean");
            } else if (typeof response[key] == "file") {
                responsePannel.find(".pannel_param_type").val("file");
            } else if (typeof response[key] == "float") {
                responsePannel.find(".pannel_param_type").val("float");
            } else if (typeof response[key] == "double") {
                responsePannel.find(".pannel_param_type").val("double");
            } else if (typeof response[key] == "integer") {
                responsePannel.find(".pannel_param_type").val("integer");
            }
            responsePannel.find(".response_param_button").trigger("click");
        });
    }


    // setTimeout(function(){
    //     $(".loader_container").hide();
    // }, 3000);
});

$(".new_document_icon_container").on("click", function() {
    $(".newdoc_modalbox_overlay").fadeIn();
    $(".newdoc_modalbox").animate({"top":"50%"});
});

$(document).on("click", ".newdoc_modalbox_overlay", function(e) {
    if (e.target === this) {
        $(".newdoc_modalbox").animate({"top":"-50%"}, () => {
            $(".newdoc_modalbox_overlay").fadeOut();
        });
    }
});

$(document).on("click", ".newdoc_modalbox_footer_button", function(){
    var error = false, errormsg = "", docName = $("#newdoc_doc_name"), domainUrl = $("#newdoc_domain_url"), docDescription = $("#newdoc_description"), docGroupName = $("#newdoc_group_name"), docGroupDescription = $("#newdoc_group_description");
    $(".newdoc_modalbox").find(".input_error").removeClass("input_error");
    if (docName.val() == "") {
        error = true;
        errormsg = "Document name is required field.";
        docName.addClass("input_error");
        $("html, body").animate({
            scrollTop: docName.offset().top
        }, 300);
    } else if (domainUrl.val() == "") {
        error = true;
        errormsg = "Domain Url is required field.";
        domainUrl.addClass("input_error");
        $("html, body").animate({
            scrollTop: domainUrl.offset().top
        }, 300);
    }
    if ($("#newdoc_doc_copy").val().trim() == "") {
        if (docDescription.val() == "") {
            error = true;
            errormsg = "Document description is required field.";
            docDescription.addClass("input_error");
            $("html, body").animate({
                scrollTop: docDescription.offset().top
            }, 300);
        } else if (docGroupName.val() == "") {
            error = true;
            errormsg = "Group name is required field.";
            docGroupName.addClass("input_error");
            $("html, body").animate({
                scrollTop: docGroupName.offset().top
            }, 300);
        } else if (docGroupDescription.val() == "") {
            error = true;
            errormsg = "Group description is required field.";
            docGroupDescription.addClass("input_error");
            $("html, body").animate({
                scrollTop: docGroupDescription.offset().top
            }, 300);
        }
    }
    if (!error) {
        $(".loader_container").show();
        $.ajax({
            url: "write",
            type: "POST",
            dataType: "json",
            data: {
                db : db,
                action: "createnew",
                docName: docName.val().trim(),
                domainUrl: domainUrl.val().trim(),
                docGroupName: docGroupName.val().trim(),
                copyof: $("#newdoc_doc_copy").val().trim(),
                docDescription: docDescription.val().trim(),
                docGroupDescription: docGroupDescription.val().trim()
            },
            success: function(apiData){
                $(".loader_container").hide();
                if (apiData["success"]) {
                    successNotification(apiData["message"]);
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                } else {
                    errorNotification(apiData["message"]);
                    docName.addClass("input_error");
                    $("html, body").animate({
                        scrollTop: docName.offset().top
                    }, 300);
                }
            },
            error: function(){
                $(".loader_container").hide();
                errorNotification("Sorry something went wrong.");
            }
        });
    } else {
        errorNotification(errormsg);
    }
});
var db = $(".form_selection").val();

$(".api_each_group").on("click", function(e) {
    if ($(e.target).hasClass("api_group_label") || $(e.target).hasClass("api_group_action") || $(e.target).hasClass("api_each_group")) {
        if ($(this).find(".api_group_action").text() == "keyboard_arrow_down") {
            $(this).find(".api_group_action").text("keyboard_arrow_up");
            $(this).find(".api_group_apilist").slideDown("slow");
        } else {
            $(this).find(".api_group_action").text("keyboard_arrow_down");
            $(this).find(".api_group_apilist").slideUp("slow");
        }
    }
});

$(window).bind("load", function() {
    var url = window.location.href.split("#");
    var apiIndex = 0;
    var groupIndex = 0;
    if (url.length > 1) {
        urlArr = url[1].split("_");
        groupIndex = urlArr[0];
        apiIndex = urlArr[1];
    }
    $(".api_group_container").find(".api_each_group").eq(groupIndex).trigger("click");
    $(".api_group_container").find(".api_each_group").eq(groupIndex).find(".api_group_each_api_container").eq(apiIndex).trigger("click");
});

$(".api_group_each_api_container").on("click", function() {
    $(".loader_container").show();
    var thisThis = $(this);
    $(".api_group_each_api_container").removeClass("api_selected")
    thisThis.addClass("api_selected");
    var groupIndex = thisThis.parents(".api_each_group").attr("data-index");
    var apiIndex = thisThis.attr("data-index");
    window.location = "#"+groupIndex+"_"+apiIndex;
    var options = {
        collapsed: false,
        withQuotes: false
    };
    setTimeout(() => {
        $.ajax({
            url: "read",
            type: "POST",
            dataType: "json",
            data: {
                db : db,
                for: "read",
                apiIndex : apiIndex,
                groupIndex : groupIndex
            },
            success: function(apiData){
                $(".loader_container").hide();
                $(".main_content").removeClass("hidden");
                $(".api_name_heading").text(apiData["api-name"]);
                $(".group_label").text(apiData["group-name"]);
                $(".api_endpoint").text(apiData["api-endpoint"]);
                $(".api_description").text(apiData["api-description"]);
                $(".group_description").text(apiData["group-description"]);
                if (apiData["api-method"] == "GET") {
                    $(".api_name_heading").removeClass("api_name_post api_name_delete api_name_put");
                    $(".api_name_heading").addClass("api_name_get");
                    $(".api_name_conatiner").removeClass("api_name_conatiner_post api_name_conatiner_delete api_name_conatiner_put");
                    $(".api_name_conatiner").addClass("api_name_conatiner_get");
                    $(".api_method").html("<label class='get_label api_method_label'>GET</label>");
                } else if (apiData["api-method"] == "POST") {
                    $(".api_name_heading").removeClass("api_name_get api_name_delete api_name_put");
                    $(".api_name_heading").addClass("api_name_post");
                    $(".api_name_conatiner").removeClass("api_name_conatiner_get api_name_conatiner_delete api_name_conatiner_put");
                    $(".api_name_conatiner").addClass("api_name_conatiner_post");
                    $(".api_method").html("<label class='post_label api_method_label'>POST</label>");
                } else if (apiData["api-method"] == "DELETE") {
                    $(".api_name_heading").removeClass("api_name_post api_name_get api_name_put");
                    $(".api_name_heading").addClass("api_name_delete");
                    $(".api_name_conatiner").removeClass("api_name_conatiner_post api_name_conatiner_get api_name_conatiner_put");
                    $(".api_name_conatiner").addClass("api_name_conatiner_delete");
                    $(".api_method").html("<label class='delete_label api_method_label'>DELETE</label>");
                } else if (apiData["api-method"] == "PUT") {
                    $(".api_name_heading").removeClass("api_name_post api_name_get api_name_delete");
                    $(".api_name_heading").addClass("api_name_put");
                    $(".api_name_conatiner").removeClass("api_name_conatiner_post api_name_conatiner_get api_name_conatiner_delete");
                    $(".api_name_conatiner").addClass("api_name_conatiner_put");
                    $(".api_method").html("<label class='put_label api_method_label'>PUT</label>");
                }
// request headers ////////////////////////////////////////////////////////////////////////////////
                var requestHeadersHtml = "";
                if (apiData["request-header-params"].length) {
                    for (var i=0; i<apiData["request-header-params"].length; i++) {
                        var header = apiData["request-header-params"][i];
                        var example = header["possible-values"] ? header["possible-values"] : "";
                        if (typeof header["required"] != "undefined" && header["required"] == true) {
                            requestHeadersHtml += "<tr><td>"+header["key-name"]+"<em>*</em></td>";
                        } else {
                            requestHeadersHtml += "<tr><td>"+header["key-name"]+"</td>";
                        }
                        requestHeadersHtml += "<td>"+header["data-type"]+"</td><td>"+header["description"]+"</td><td><pre class='possible_values'>"+example+"</pre></td></tr>";
                    }
                } else {
                    requestHeadersHtml = "<tr><td class='emptyRow' colspan='4'>No Request Headers Available</td></tr>";
                }
                $(".api_request_header_table tbody").html(requestHeadersHtml);
// request params /////////////////////////////////////////////////////////////////////////////////
                var requestParamsHtml = "";
                if (apiData["api-params"].length) {
                    for (var i=0; i<apiData["api-params"].length; i++) {
                        var param = apiData["api-params"][i];
                        var example = param["possible-values"] ? param["possible-values"] : "";
                        if (typeof param["required"] != "undefined" && param["required"] == true) {
                            requestParamsHtml += "<tr><td>"+param["key-name"]+"<em>*</em></td>";
                        } else {
                            requestParamsHtml += "<tr><td>"+param["key-name"]+"</td>";
                        }
                        requestParamsHtml += "<td>"+param["data-type"]+"</td><td>"+param["description"]+"</td><td><pre class='possible_values'>"+example+"</pre></td></tr>";
                    }
                } else {
                    requestParamsHtml = "<tr><td class='emptyRow' colspan='4'>No Request Params Available</td></tr>";
                }
                $(".api_request_param_table tbody").html(requestParamsHtml);
// response headers ///////////////////////////////////////////////////////////////////////////////
                var responseHeadersHtml = "";
                if (apiData["response-header-params"].length) {
                    for (var i=0; i<apiData["response-header-params"].length; i++) {
                        var header = apiData["response-header-params"][i];
                        var example = header["possible-values"] ? header["possible-values"] : "";
                        responseHeadersHtml += "<tr><td>"+header["key-name"]+"</td><td>"+header["data-type"]+"</td><td>"+header["description"]+"</td><td><pre class='possible_values'>"+example+"</pre></td></tr>";
                    }
                } else {
                    responseHeadersHtml = "<tr><td class='emptyRow' colspan='4'>No Response Headers Available</td></tr>";
                }
                $(".api_response_header_table tbody").html(responseHeadersHtml);
// response params ////////////////////////////////////////////////////////////////////////////////
                var responseParamsHtml = "";
                if (apiData["api-response"].length) {
                    for (var i=0; i<apiData["api-response"].length; i++) {
                        var param = apiData["api-response"][i];
                        var example = param["possible-values"] ? param["possible-values"] : "";
                        responseParamsHtml += "<tr><td>"+param["key-name"]+"</td><td>"+param["data-type"]+"</td><td>"+param["description"]+"</td><td><pre class='possible_values'>"+example+"</pre></td></tr>";
                    }
                } else {
                    responseParamsHtml = "<tr><td class='emptyRow' colspan='4'>No Response Params Available</td></tr>";
                }
                $(".api_response_param_table tbody").html(responseParamsHtml);
// formatting example values //////////////////////////////////////////////////////////////////////
                $(".api_request_response_details_container .possible_values").each(function(){
                    var target = $(this),
                        targetValue = target.text();
                    if (targetValue != "" && isJson(targetValue)) {
                        target.jsonViewer(JSON.parse(targetValue), options);
                    }
                });
// example values /////////////////////////////////////////////////////////////////////////////////
                $(".example_container pre").html(JSON.stringify(apiData["possible-values"]));
                $(".example_container pre").jsonViewer(JSON.parse($(".example_container pre").text()), options);

                $(".expander").trigger("click");
            },
            error: function(data){
                $(".loader_container").hide();
            }
        });
    }, 1000);
});

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

$(".form_selection").on("change", function() {
    location.href = currentUrl+"?db="+$(this).val();
});

$(".app_desc_toggle_button").on("click", function(){
    var thisThis = $(this);
    if (thisThis.text() == "remove_circle_outline") {
        thisThis.text("add_circle_outline");
        thisThis.next("div").slideUp();
    } else {
        thisThis.text("remove_circle_outline");
        thisThis.next("div").slideDown();
    }
});

$(".toolbar_button").on("click", function(){
    var thisThis = $(this);
    $(".toolbar_button").removeClass("active");
    thisThis.addClass("active");
    if (thisThis.hasClass("collapser")) {
        $(".app_desc_toggle_button").each(function(){
            if ($(this).text() == "remove_circle_outline"){
                $(this).trigger("click");
            }
        });
    } else if (thisThis.hasClass("expander")) {
        $(".app_desc_toggle_button").each(function(){
            if ($(this).text() != "remove_circle_outline"){
                $(this).trigger("click");
            }
        });
    }
});

var searchInterval;
$(".search_input").on("keyup", function() {
    var queryString = $(this).val().toLowerCase().trim();
    clearInterval(searchInterval);
    searchInterval = setInterval(() => {
        $(".api_group_container").find(".api_group_each_apiname").each(function(){
            var thisThis = $(this);
            var thisGroup = thisThis.parents(".api_each_group");
            var apiName = thisThis.text().toLowerCase().trim();
            if (apiName.includes(queryString)) {
                thisThis.parents(".api_group_each_api_container").removeClass("hidden");
                if (queryString !== "" && thisGroup.find(".api_group_action").text() == "keyboard_arrow_down") {
                    thisGroup.trigger("click");
                }
            } else {
                thisThis.parents(".api_group_each_api_container").addClass("hidden");
            }
            if (queryString === "" && thisGroup.find(".api_group_action").text() == "keyboard_arrow_up") {
                if (thisGroup.attr("data-index") != 0) {
                    thisGroup.trigger("click");
                }
            }
        });
        clearInterval(searchInterval);
        return false;
    }, 500);
});
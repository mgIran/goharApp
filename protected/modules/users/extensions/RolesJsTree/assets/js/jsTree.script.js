$(function(){
    $("#modules_tree").jstree({
        "plugins":["wholerow","checkbox"],
        "core": {
            "themes": {
                "name": "proton",
                "responsive": true
            }
        }
    });
    $("#modules_tree").jstree().close_all();
    /*setTimeout(function(){
        $("#modules_tree").jstree().open_all();
        $('li[data-checked="true"]').each(function() {
            $("#modules_tree").jstree('check_node', $(this));
        });
        $("#modules_tree").jstree().close_all();
    },3000);*/
    $("body").on("click",'input[type="submit"]',function(){
        var selectedElmsIds = new Object();
        var selectedElms = $("#modules_tree").jstree("get_selected", true);

        var moduleName = "";
        $.each(selectedElms, function() {
            separator = this.id.indexOf("$$");
            if(separator != -1)
            {
                moduleName = this.id.substring(0,separator);
                permissionName = this.id.substr(separator + 2);
                if(eval("selectedElmsIds." + moduleName) == undefined)
                    eval("selectedElmsIds." + moduleName + " = new Array()");
                eval("selectedElmsIds." + moduleName + ".push(\'" + permissionName + "\')");
            }
        });
        $("#js-tree-permissions").val(JSON.stringify(selectedElmsIds));
    });
});
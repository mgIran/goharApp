$(function(){
    $("#modules_tree").jstree({
        "plugins":["wholerow","checkbox"],
        "core": {
            "themes": {
                "name": "proton",
                "responsive": true
            }
        },
        "checkbox": {
            "tie_selection": false
        }
    });
    $("#modules_tree").jstree().close_all();
    $('#modules_tree').on('select_node.jstree',function(e, data){
        e.preventDefault();
        $('#modules_tree').jstree(true).deselect_node(data.node);
        $("#" + data.node.id).find(".jstree-anchor:first").toggleClass('js-tree-clicked');
        return false;
    });

    $(document).on("click",'input[type="submit"]',function(e){
        var selectedElmsIds = new Array();
        $(".jstree-anchor.js-tree-clicked").each(function() {
            var $id = $(this).closest(".jstree-node").attr("id");
            separator = $id.indexOf("--");
            if(separator != -1)
            {
                var catId = $id.substr(separator + 2);
                selectedElmsIds.push(catId);
            }
        });
        $("#js-tree-permissions").val(JSON.stringify(selectedElmsIds));

    });
});
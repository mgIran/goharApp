<div class="modal fade" id="from-contacts" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-left" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$title?></h4>
            </div>
            <div class="modal-body">
                <div class="form">
                    <?
                    $js =
                    'function(e){
                        var selectedElmsIds = {category:new Array(),contact:new Array()};
                        $("#contactsTree .jstree-anchor.jstree-clicked").each(function() {

                            var $item = $(this).closest(".jstree-node");
                            var $id = $item.attr("id");
                            var $type = $item.data("type");
                            if($type == "contact")
                            {
                                if(!$(this).parents("li[data-type=\'category\']").find(".jstree-anchor:first").hasClass("jstree-clicked"))
                                    selectedElmsIds.contact.push($id);
                            }
                            else
                                selectedElmsIds.category.push($id);
                        });
                        $("#MessagesEmailsSend_contacts").val(JSON.stringify(selectedElmsIds));

                    }';
                    $this->widget('application.modules.messages.extensions.RolesJsTree.RolesJsTree', array(
                        'name' => 'MessagesEmailsSend[contacts]',
                        'classes' => $contactsCategories,
                        'id' => 'contactsTree',
                        'inputId'=>'MessagesEmailsSend_contacts',
                        'itemsPrefixId' => 'contacts_email' ,
                        'ajax' =>
                            "'data' : {
                                'dataType' : 'JSON',
                                'url' : function (node) {
                                    return node.id === '#' ?
                                        '".Yii::app()->createAbsoluteUrl("messages/emails_send/getContactsCategories")."' :
                                        '".Yii::app()->createAbsoluteUrl("messages/emails_send/getContacts?id=")."' + node.id;
                                    }
                                }
                            ",
                        'onChanged' => $js,
                    ));
                    ?>
                </div>
            </div>
            <div class="modal-footer" style="text-align: left;padding-left: 5%">
                <button type="button" class="btn btn-primary" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>

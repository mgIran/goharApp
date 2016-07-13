<?
Yii::app()->clientScript->registerCss('jsTree',"
#modules_tree{
    border:none !important;
    border-radius:0;
    background:transparent;
    width:100%;
}
");
?>
<div class="modal fade" id="from-mobile-bank" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-left" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$title?></h4>
            </div>
            <div class="modal-body">
                <div class="form">
                    <?
                    $this->widget('application.modules.messages.extensions.RolesJsTree.RolesJsTree', array(
                        'name' => 'MessagesTextsSend[bank]',
                        'classes' => $mobilesBankCategories,
                        'id' => 'mobilesBankTree',
                        'itemsPrefixId' => 'bank_mobile' ,
                        'inputId' => 'MessagesTextsSend_bank',
                        'isBank' => TRUE
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

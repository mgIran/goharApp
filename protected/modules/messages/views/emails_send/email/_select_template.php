<div class="modal fade" id="select-template" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-left" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$title?></h4>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    <?foreach($templates as $template):?>
                        <a href="#" data-id="<?=$template->id?>" class="list-group-item">
                            <?=$template->title?>
                        </a>
                    <?endforeach;?>
                </div>
            </div>
            <div class="modal-footer" style="text-align: left;padding-left: 5%">
                <button type="button" class="btn btn-primary" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>
<?
Yii::app()->clientScript->registerScript("selectTemplate","

    $('#select-template .list-group-item:not(.active)').on('click',function(){
        $.ajax({
            url:'".Yii::app()->createAbsoluteUrl("messages/emails_send/getTemplate/?id=")."' + $(this).data('id'),
            success:function(data){
                $('#select-template').modal('hide');
                CKEDITOR.instances['MessagesEmailsSend_body'].setData(data);
            }
        });
        $('.list-group-item').removeClass('active');
        $(this).addClass('active');
        return false;
    });
");
?>
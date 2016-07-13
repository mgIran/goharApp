<?
//Yii::app()->clientScript->registerScript('uploadFile',"
//");
Yii::app()->clientScript->registerCss("uploadFile","
    .iwfu-file-gallery-container{
        display: none !important;
    }
    .iwfu-result-message{
        position:relative !important;
    }
");
?>
<div class="modal fade" id="from-file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 350px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-left" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$title?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?
                    $this->widget('application.extensions.iWebUploader.iWebUploader',
                        array(
                            'type' => 'file',
                            'upload' => array(
                                'url' => 'upload',
                                'allowedFileTypes' => array('text/plain'),
                                'allowedFileExtensions' => array('.txt'),
                                'queueFiles' => 1,
                                'afterUpload' => 'function(result){
                                    var selectize_tags = $("#MessagesMobilesSend_to")[0].selectize;
                                    files = result.fileName;
                                    $(files).each(function(){
                                        selectize_tags.addOption({text:this,value:this});
                                        selectize_tags.refreshOptions();
                                        selectize_tags.addItem(this);
                                        selectize_tags.refreshItems();
                                    });
                                    if(result.result)
                                        $("#from-file").modal("hide");
                                }'
                            ),
                            'delete' => array(
                                'url' => '#'
                            )
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="upload-checkouts" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-left" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">
                    ایمپورت
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 pull-right">
                        پسوند های مجاز
                        <span class="iwsfu-drop-zone" style="border: none;padding: 0;margin: 0;display: inline">
                            <span class="iwsfu-formats" style="display: inline;">
                                <span>TXT</span>
                            </span>
                        <span>
                    </div>
                </div>
                <br/>
                    <?
                    $this->widget('application.extensions.iWebUploader.iWebUploader',
                        array(
                            'type' => 'singleFile',
                            'upload' => array(
                                'url' => Yii::app()->createAbsoluteUrl('financial/checkouts/import'),
                                'data' => 'js:{id:function(){return importId}}',
                                'afterUploadFinished' => 'js:function(){
                                    $.fn.yiiGridView.update("checkouts-export-grid");
                                    $("#upload-checkouts").modal("hide");
                                }',
                                //'allowedFileTypes' => array('application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
                                //'allowedFileExtensions' => array('.xls', '.xlsx'),
                                //'allowedFileTypes' => array('text/html'),
                                //'allowedFileExtensions' => array('.html'),
                                'allowedFileTypes' => array('text/plain'),
                                'allowedFileExtensions' => array('.txt'),
                                'queueFiles' => 1,
                                'maxFiles' => 1,
                                'maxFileSize' => 1,
                            ),
                            'delete' => array(
                                'url' => Yii::app()->createAbsoluteUrl('financial/checkouts/import_delete'),
                            )
                        )
                    );
                    ?>
            </div>
        </div>
    </div>
</div>
<?
Yii::app()->clientScript->registerScript("upload",'
    var importId = null;
',CClientScript::POS_HEAD);
?>

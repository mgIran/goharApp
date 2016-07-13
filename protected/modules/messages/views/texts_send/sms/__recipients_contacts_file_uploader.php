<div class="container-fluid">
    <div class="row" style="margin: 15px 0;font-size: 11px;">
        <div class="col-md-2 pull-right">
            <span class="pull-left" style="margin-left: 5px">پسوند های مجاز</span>
        </div>
        <div class="col-md-3 pull-right">
            <span class="iwsfu-drop-zone" style="border: none;padding: 0;margin: 0;display: inline">
                <span class="iwsfu-formats" style="display: inline;">
                    <span>XLS</span>
                    <span>XLSX</span>
                    <span>TXT</span>
                </span>
            </span>
        </div>
        <div class="col-md-2 pull-right" style="margin-right: -6px">
            <span class="pull-left">حداکثر حجم فایل</span>
        </div>
        <div class="col-md-2 pull-right">
            <span class="iwsfu-drop-zone" style="border: none;padding: 0;margin: 0;display: inline;">
                <span class="iwsfu-formats" style="display: inline;">
                    <span>2MB</span>
                </span>
            </span>
        </div>
    </div>
    <?php $this->widget('application.extensions.iWebUploader.iWebUploader', array(
        'type' => 'singleFile',
        'upload' => array(
            'url' => Yii::app()->createUrl('/messages/texts_send/addRecipientsFromFile'),
            'allowedFileTypes' => array('text/plain', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
            'allowedFileExtensions' => array('.txt', '.xls', '.xlsx'),
            'queueFiles' => 1,
            'maxFiles' => 1,
            'maxFileSize' => 2,
            'data'=>'js:{sid:$("#sid").val()}',
            'afterUploadFinished'=>"js:function(){
                $('.iwsfu-files-item').remove();
            }"
        ),
        'delete'=>array(
            'url'=>''
        ),
    ));?>
    <div class="alert alert-danger" style="padding: 10px 15px;margin-top: 15px;">
        <strong>توجه:</strong>Encoding فایل TXT باید ANSI باشد.
    </div>
</div>
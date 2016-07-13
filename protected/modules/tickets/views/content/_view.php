    <hr style="border-color: #999"/>
    <div class="row">
        <div class="col-md-6 pull-right">
            <label class="pull-right control-label">نام و نام خانوادگی : </label>
            &nbsp;<?= (!is_null($data->admin_id))? $data->admin->first_name .' ' . $data->admin->last_name :$data->ticket->user->first_name.' '.$data->ticket->user->last_name ?>
        </div>
        <div class="col-md-6">
            <label class="pull-right control-label">تاریخ :  </label>
            &nbsp;<?= Yii::app()->jdate->date('Y-m-d H:i', $data->date) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12" style="line-height: 25px">
            <?= nl2br($data->text)?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12">
            <?if(isset($data->file) AND !is_null($data->file) AND $data->file!=''):?>
                <a class="attached-file btn btn-info" href="<?= Yii::app()->getBaseUrl(TRUE).'/upload/files/'.$data->file?>" target="_blank">
                    <i class="fa fa-file"></i>
                    فایل ضمیمه
                </a>
            <?endif?>
        </div>
    </div>

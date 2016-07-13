<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,$type); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?
        $userId = "";
        if($this->id=='manage' AND $this->action->id == 'update' AND isset($_GET['id']))
            $userId = "&user_id=".$_GET['id'];

        $uploaded = array();

        if(Yii::app()->user->type == 'user')
            $id = Yii::app()->user->userID;
        else
            $id = $_GET['id'];

        if(!is_null($model->$type))
            $uploaded = array(
                'id' => 1,
                'name' => $model->$type
            );
        $array = array(
            'type' => 'singleFile',
            'upload' => array(
                'url' => Yii::app()->createAbsoluteUrl('/users/account/upload?type='.$type.$userId),
                'allowedFileTypes' => array('image/jpeg', 'image/png', 'image/gif'),
                'allowedFileExtensions' => array('.jpg', '.jpeg', '.png', '.gif'),
                'queueFiles' => 1,
                'maxFiles' => 1,
                'maxFileSize' => 0.3,
                'downloadUrl' => Yii::app()->createAbsoluteUrl('upload/users/legal_documents/'.$id.'/')
            ),
            'uploadedFiles' => $uploaded,
        );

        $array['delete'] = array(
            'url' => (Yii::app()->user->type == 'admin')?Yii::app()->createAbsoluteUrl('/users/account/delete?type='.$type.$userId):"#",
        );

        $this->widget('application.extensions.iWebUploader.iWebUploader',$array);
        ?>
    </div>
</div>
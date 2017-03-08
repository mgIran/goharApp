<?php
/* @var $this UnityManageController */
/* @var $model Unity */
/* @var $form CActiveForm */
/* @var $poster array */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'unity-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">فیلد های <span class="required">*</span>دار اجباری هستند.</p>

    <?php $this->renderPartial("//layouts/_flashMessage");?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>511)); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
        <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
            'id'=>'date',
            'model'=>$model,
            'attribute'=>'date',
            'options'=>array(
                'format'=>'DD MMMM YYYY - H:m',
                'timePicker'=>array(
                    'enabled'=>true
                )
            ),
        ));?>
		<?php echo $form->error($model,'date'); ?>
	</div>

    <div class="row">
		<?php echo $form->labelEx($model,'notices_date'); ?>
        <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
            'id'=>'notices-date',
            'model'=>$model,
            'attribute'=>'notices_date',
            'options'=>array(
                'format'=>'DD MMMM YYYY'
            ),
        ));?>
		<?php echo $form->error($model,'notices_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'poster'); ?>
		<?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
			'id' => 'poster-uploader',
			'model' => $model,
			'name' => 'poster',
			'maxFiles' => 1,
			'maxFileSize' => 1, //MB
			'url' => $this->createUrl('/unity/manage/upload'),
			'deleteUrl' => $this->createUrl('/unity/manage/deleteUpload'),
			'acceptedFiles' => '.jpeg, .jpg, .png, .gif',
			'serverFiles' => $poster,
			'onSuccess' => '
				var responseObj = JSON.parse(res);
				if(responseObj.status){
					{serverName} = responseObj.fileName;
					$(".uploader-message").html("");
				}
				else{
					$(".uploader-message").html(responseObj.message);
                    this.removeFile(file);
                }
		')); ?>
		<?php echo $form->error($model,'poster'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره', array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
/* @var $this NotificationsManageController */
/* @var $model Notifications */
/* @var $form CActiveForm */
/* @var $poster array */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'notifications-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<?php $this->renderPartial('//layouts/_flashMessage'); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>511)); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'send_date'); ?>
		<?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
			'id'=>'send-date',
			'model'=>$model,
			'attribute'=>'send_date',
			'options'=>array(
				'format'=>'DD MMMM YYYY'
			),
		));?>
		<?php echo $form->error($model,'send_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'expire_date'); ?>
		<?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
			'id'=>'expire-date',
			'model'=>$model,
			'attribute'=>'expire_date',
			'options'=>array(
				'format'=>'DD MMMM YYYY'
			),
		));?>
		<?php echo $form->error($model,'expire_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model, 'status', $model->statusLabels) ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'poster'); ?>
		<?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
			'id' => 'poster-uploader',
			'model' => $model,
			'name' => 'poster',
			'maxFiles' => 1,
			'maxFileSize' => 1, //MB
			'url' => $this->createUrl('/notifications/manage/upload'),
			'deleteUrl' => $this->createUrl('/notifications/manage/deleteUpload'),
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
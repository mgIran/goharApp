<?php
/* @var $this PlacesController */
/* @var $model Places */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-places-form',
	'enableAjaxValidation'=>true,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'parent_id'); ?>
        <?php echo $form->dropDownList($model, 'parent_id',UsersPlaces::getList()); ?>
		<?php echo $form->error($model,'parent_id'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'national_id_prefix'); ?>
        <?php echo $form->textField($model, 'national_id_prefix'); ?>
        <?php echo $form->error($model,'national_id_prefix'); ?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'افزودن' : 'ذخیره'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
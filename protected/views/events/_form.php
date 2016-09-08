<?php
/* @var $this EventsController */
/* @var $model Events */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'events-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'organizer'); ?>
		<?php echo $form->textField($model,'organizer',array('size'=>60,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'organizer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sex'); ?>
		<?php echo $form->textField($model,'sex',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'sex'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ages'); ?>
		<?php echo $form->textField($model,'ages',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'ages'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_date_run'); ?>
		<?php echo $form->textField($model,'start_date_run',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'start_date_run'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'duration'); ?>
		<?php echo $form->textField($model,'duration',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'duration'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_time_run'); ?>
		<?php echo $form->textField($model,'start_time_run',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'start_time_run'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'end_time_run'); ?>
		<?php echo $form->textField($model,'end_time_run',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'end_time_run'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_more_days'); ?>
		<?php echo $form->textField($model,'max_more_days',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'max_more_days'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'more_days'); ?>
		<?php echo $form->textField($model,'more_days',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'more_days'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'state_id'); ?>
		<?php echo $form->textField($model,'state_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'state_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city_id'); ?>
		<?php echo $form->textField($model,'city_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'city_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'town'); ?>
		<?php echo $form->textField($model,'town',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'town'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'main_street'); ?>
		<?php echo $form->textField($model,'main_street',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'main_street'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'by_street'); ?>
		<?php echo $form->textField($model,'by_street',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'by_street'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'boulevard'); ?>
		<?php echo $form->textField($model,'boulevard',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'boulevard'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'afew_ways'); ?>
		<?php echo $form->textField($model,'afew_ways',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'afew_ways'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'squary'); ?>
		<?php echo $form->textField($model,'squary',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'squary'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bridge'); ?>
		<?php echo $form->textField($model,'bridge',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'bridge'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'quarter'); ?>
		<?php echo $form->textField($model,'quarter',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'quarter'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'area_code'); ?>
		<?php echo $form->textField($model,'area_code',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'area_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postal_code'); ?>
		<?php echo $form->textField($model,'postal_code',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'postal_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'complete_address'); ?>
		<?php echo $form->textArea($model,'complete_address',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'complete_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'complete_details'); ?>
		<?php echo $form->textArea($model,'complete_details',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'complete_details'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'reception'); ?>
		<?php echo $form->textField($model,'reception',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'reception'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'invitees'); ?>
		<?php echo $form->textArea($model,'invitees',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'invitees'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'activator_area_code'); ?>
		<?php echo $form->textField($model,'activator_area_code'); ?>
		<?php echo $form->error($model,'activator_area_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'activator_postal_code'); ?>
		<?php echo $form->textField($model,'activator_postal_code'); ?>
		<?php echo $form->error($model,'activator_postal_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ceremony_poster'); ?>
		<?php echo $form->textField($model,'ceremony_poster',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'ceremony_poster'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
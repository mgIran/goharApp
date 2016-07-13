<?php
/* @var $this CostsController */
/* @var $model Costs */


$this->menu=array(
	array('label'=>'افزودن هزینه', 'url'=>array('create')),
	array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<?php
/* @var $this CostsController */
/* @var $model Costs */
/* @var $form CActiveForm */
?>
<? $this->widget("ext.iWebFunctions.iWebFunctions");?>
<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'costs-form',
		'htmlOptions' => array(
			'class' => 'col-md-6 col-md-offset-2'
		),
		'enableAjaxValidation'=>false,
	)); ?>

	<div class="row">
		<h6 class="pull-left">ویرایش</h6>
	</div>

	<div class="row errors">
		<div class="col-md-4 pull-right"></div>
		<div class="col-md-8">
			<?php echo $form->error($model,'title'); ?>
			<?php echo $form->error($model,'status'); ?>
			<?php echo $form->error($model,'price'); ?>
			<?php echo $form->error($model,'qty'); ?>
			<?php echo $form->error($model,'start_date'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4 pull-right pull-right">
			<?php echo $form->labelEx($model,'title'); ?>
		</div>
		<div class="col-md-8">
			<?php echo $form->textField($model,'title',array('class'=>'form-control','placeholder'=>'Title...','readonly'=>'readonly')); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4 pull-right">
			<?php echo $form->labelEx($model,'status'); ?>
		</div>
		<div class="col-md-8">
			<?php echo $form->textField($model,'status',array('class'=>'form-control','placeholder'=>'Title...','readonly'=>'readonly','value'=>Costs::$statusList[$model->status])); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4 pull-right">
			<?php
			$model->price = number_format($model->price);
			echo $form->labelEx($model,'price'); ?>
		</div>
		<div class="col-md-8">
			<?php echo $form->textField($model,'price',array('class'=>'form-control direct-ltr','placeholder'=>'Price...','readonly'=>'readonly')); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4 pull-right">
			<?php echo $form->labelEx($model,'qty'); ?>
		</div>
		<div class="col-md-8">
			<?php echo $form->textField($model,'qty',array('class'=>'form-control direct-ltr just-number','placeholder'=>'Installments Quantity...')); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4 pull-right col-md-offset-8">
			<?php echo CHtml::submitButton('ویرایش', array('class'=>'form-control btn btn-default submit')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->
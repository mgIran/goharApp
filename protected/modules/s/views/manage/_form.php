<?php
/* @var $this AdminsManageController */
/* @var $model Admins */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerScript('resetForm','document.getElementById("admins-form").reset();');
?>
<? $this->renderPartial('//layouts/_flashMessage'); ?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'admins-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
    'clientOptions' => array(
        'validateOnSubmit' => true
    )

)); ?>

	<div class="row form-group">
		<?php echo $form->labelEx($model,'username' ,array('class'=>'col-lg-2 control-label')); ?>
		<?php echo $form->textField($model,'username',array('size'=>50,'maxlength'=>100 , (!$model->isNewRecord?'disabled':'s') => true)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>
    <?php
    if(!$model->isNewRecord){
    ?>
        <div class="row form-group">
            <?php echo $form->labelEx($model,'oldPassword',array('class'=>'col-lg-2 control-label')); ?>
            <?php echo $form->passwordField($model,'oldPassword',array('size'=>50,'maxlength'=>100)); ?>
            <?php echo $form->error($model,'oldPassword'); ?>
        </div>
        <div class="row form-group">
            <?php echo $form->labelEx($model,'newPassword',array('class'=>'col-lg-2 control-label')); ?>
            <?php echo $form->passwordField($model,'newPassword',array('size'=>50,'maxlength'=>100)); ?>
            <?php echo $form->error($model,'newPassword'); ?>
        </div>
    <?php
    }else{
    ?>
	<div class="row form-group">
		<?php echo $form->labelEx($model,'password',array('class'=>'col-lg-2 control-label')); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>50,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

    <?php } ?>

    <div class="row form-group">
        <?php echo $form->labelEx($model,'repeatPassword',array('class'=>'col-lg-2 control-label')); ?>
        <?php echo $form->passwordField($model,'repeatPassword',array('size'=>50,'maxlength'=>100)); ?>
        <?php echo $form->error($model,'repeatPassword'); ?>
    </div>

    <div class="row form-group">
        <?php echo $form->labelEx($model,'email',array('class'=>'col-lg-2 control-label')); ?>
        <?php echo $form->emailField($model,'email',array('size'=>50,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

    <div class="row ">
        <?php echo $form->labelEx($model,'role_id',array('class'=>'col-lg-2 control-label')); ?>
        <?php echo $form->dropDownList($model,'role_id' ,CHtml::listData(  AdminRoles::model()->findAll() , 'id' , 'name')); ?>
        <?php echo $form->error($model,'role_id'); ?>
    </div>

	<div class="row form-group buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره', array('class' => 'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
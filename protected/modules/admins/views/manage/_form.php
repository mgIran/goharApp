<?
Yii::app()->clientScript->registerScript('pageScripts', "
if($('#Admins_avatar').attr('value') && $('#Admins_avatar').val()!=='')
    $('.image-crop-form').attr('style','display: block!important');
");
?>


<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'admins-form',
        'enableAjaxValidation'=>(($model->scenario=='register')?false:true),
        'enableClientValidation' => true,
        'htmlOptions' => array(
            'class' => 'col-md-6 col-md-offset-4'
        ),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        )
    )); ?>

    <div class="row">
        <h6 class="pull-left"><?=static::$actionsArray[$this->action->id]['title']?></h6>
    </div>

    <div class="row">
        <div id="avatar-upload">
            <a href="#" class="big-user-icon <?= (is_null($model->avatar) OR empty($model->avatar))?"default-big-user":'' ?>"  data-backdrop="static"data-toggle="modal" data-target="#upload-avatar">
                <? if (!is_null( $model->avatar) AND !empty($model->avatar)) echo '<img src="' . Yii::app()->createAbsoluteUrl('upload/admins/avatars/thumbnails_127x127/'.CHtml::encode($model->avatar)) . '" />'; ?>
            </a>
            <a id="big-user-text" href="#" data-backdrop="static" data-toggle="modal" data-target="#upload-avatar">جهت <?= (!is_null( $model->avatar) AND !empty($model->avatar))?"ویرایش":"افزودن" ?> تصویر کلیک کنید.</a>
        </div>
    </div>

    <div class="row errors">
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-8 pull-right">
            <?php echo $form->errorSummary($model); ?>
            <?php echo $form->error($model,'user_name'); ?>
            <?php echo $form->error($model,'first_name'); ?>
            <?php echo $form->error($model,'last_name'); ?>
            <?php echo $form->error($model,'mobile'); ?>
            <?php echo $form->error($model,'email'); ?>
            <?php echo $form->error($model,'password'); ?>
            <?php echo $form->error($model,'repeat_password'); ?>
            <?php echo $form->error($model,'verifyCode'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'user_name'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'user_name',array('class'=>'form-control','placeholder'=>'User Name...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'first_name'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'first_name',array('class'=>'form-control','placeholder'=>'First Name...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'last_name'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'last_name',array('class'=>'form-control','placeholder'=>'Last Name...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'role_id'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                'model' => $model,
                'label'=>((isset($model->AdminsRoles->title))?$model->AdminsRoles->title:'Role...'),
                'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                'name'=>'role_id',
                'list'=> $roles ,
                'id' => 'role_id',
            )); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'mobile'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'mobile',array('class'=>'form-control direct-ltr','placeholder'=>'Mobile...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'email'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'email',array('class'=>'form-control direct-ltr','placeholder'=>'Email...')); ?>
        </div>
    </div>

    <?php echo $form->hiddenField($model, 'passwordSet' ,array('value'=>0)); ?>
    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'password'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->passwordField($model,'password',array('value'=>'','class'=>'form-control direct-ltr','placeholder'=>'Password...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'repeat_password'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->passwordField($model,'repeat_password',array('value'=>'','class'=>'form-control direct-ltr','placeholder'=>'Password again...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-left">
            <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>

<? $this->renderPartial('_uploadAvatar',array(
    'model'=>$model,
    'isSetting' => false
)); ?>
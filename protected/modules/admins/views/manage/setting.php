<?
Yii::app()->clientScript->registerScript('pageScripts', "
if($('#Admins_avatar').attr('value') && $('#Admins_avatar').val()!=='')
    $('.image-crop-form').attr('style','display: block!important');

");
?>

<? if(($flashMessage = Yii::app()->user->getFlash('success')) !== null):?>    <div class="alert alert-success">
    <i class="fa fa-check-square-o fa-lg"></i>
    <?=$flashMessage;?>    </div>
<? endif;?><? if(($flashMessage = Yii::app()->user->getFlash('info')) !== null):?>    <div class="alert alert-info">
    <i class="fa fa-info-circle fa-lg"></i>
    <?=$flashMessage;?>    </div>
<? endif;?><? if(($flashMessage = Yii::app()->user->getFlash('danger')) !== null):?>    <div class="alert alert-danger">
    <i class="fa fa-frown-o fa-lg"></i>
    <?=$flashMessage;?>    </div>
<? endif;?>


<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'admins-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'afterValidate' => 'js:function(){
                app.formOnCenter(true);
                return true;
            }',
            'afterValidateAttribute' => 'js:function(){
                app.formOnCenter(true);
                return true;
            }'
        ),
        'htmlOptions' => array(
            'class' => 'col-md-6 col-md-offset-4'
        ),
    )); ?>

    <div class="row">
        <h6 class="pull-left"><?=static::$actionsArray[$this->action->id]['title']?></h6>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-4 pull-right">
            <a id="big-user-text" href="#" data-backdrop="static" data-toggle="modal" data-target="#upload-avatar">جهت <?= (is_null($model->avatar) OR empty($model->avatar))?"ویرایش":"افزودن" ?> تصویر کلیک کنید.</a>
            <a href="#" class="big-user-icon <?= (is_null($model->avatar) OR empty($model->avatar))?"default-big-user":'' ?>" data-backdrop="static" data-toggle="modal" data-target="#upload-avatar">
                <? if (!is_null($model->avatar) AND !empty($model->avatar)) echo '<img src="' . Yii::app()->createAbsoluteUrl('upload/Admins/avatars/thumbnails_127x127/'.CHtml::encode($model->avatar)) . '" />'; ?>
            </a>
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

    <!-- change password button-->
    <div class="row">
        <div class="col-md-8 pull-left">
            <?php echo CHtml::tag('div', array('id'=>'ch-password-btn','class'=>'form-control btn btn-default submit'),'تغییر رمز عبور'); ?>
        </div>
    </div>

    <!-- change password block -->
    <div id="ch-password-div" style="display:none">

        <?php echo $form->hiddenField($model, 'passwordSet', array('value'=>0)); ?>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'old_password'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->passwordField($model,'old_password',array('class'=>'form-control direct-ltr','placeholder'=>'Old Password...')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'password'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->passwordField($model,'password',array('class'=>'form-control direct-ltr','placeholder'=>'Password...','value'=>'')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'repeat_password'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->passwordField($model,'repeat_password',array('class'=>'form-control direct-ltr','placeholder'=>'Password again...')); ?>
            </div>
        </div>

    </div>


    <div class="row">
        <div class="col-md-4 pull-left">
            <?php echo CHtml::submitButton('ویرایش', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

    <? $this->renderPartial('_uploadAvatar',array(
        'model'=>$model,
        'isSetting' => true
    )); ?>

</div>
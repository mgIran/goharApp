<?php
CHtml::$afterRequiredLabel = '';
CHtml::$beforeRequiredLabel = '';

/*if (Yii::app()->request->isPostRequest)
    Yii::app()->clientScript->registerScript(
        'initCaptcha',
        '$(".captcha-container img").trigger("click");',
        CClientScript::POS_READY
    );*/

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
    'Login',
);
?>


<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        'enableClientValidation'=>true,
        'enableAjaxValidation'=>false,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'afterValidate' => 'js:function(form, data, hasError){
            if(hasError)
                $(".captcha-container img").trigger("click");
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
        )
    )); ?>
    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'username'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'username',array('class'=>'form-control direct-ltr padding-left-30','placeholder'=>'Email...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'password'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->passwordField($model,'password',array('class'=>'form-control direct-ltr padding-left-30','placeholder'=>'Password...')); ?>
        </div>
    </div>

    <?php if(CCaptcha::checkRequirements()): ?>
        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'verifyCode'); ?>
            </div>
            <div>
                <div class="col-md-4 pull-left captcha-container">
                    <?php $this->widget('CCaptcha',array(
                        'clickableImage' => true ,
                        'showRefreshButton' => false
                    )); ?>
                </div>
                <div class="col-md-4 pull-left" style="padding-left: 6px">
                    <?php echo $form->textField($model,'verifyCode',array('class'=>'form-control direct-ltr','placeholder'=>'Enter the code','maxlength'=>7)); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>



    <div class="row rememberMe">
        <div class="col-md-4 pull-right"></div>
        <?php echo $form->checkBox($model,'rememberMe',array('class'=>'pull-right css-checkbox','style'=>'margin-left:4px;')); ?>
        <?php echo $form->label($model,'rememberMe',array('class'=>'pull-right css-label')); ?>
        <a href="<?=Yii::app()->createAbsoluteUrl('admin/login/forgetPassword')?>" class="forget-pass pull-left" title="فراموشی رمز عبور">رمز عبور را فراموش کرده ام</a>
    </div>

    <div class="login-button-container">
        <?php echo CHtml::submitButton('ورود',array('class'=>'login-button')); ?>
    </div>
    <div class="row errors">
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-8 pull-right">
            <?php echo $form->errorSummary($model); ?>
            <?php echo $form->error($model,'username'); ?>
            <?php echo $form->error($model,'password'); ?>
            <?php echo $form->error($model,'verifyCode'); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->

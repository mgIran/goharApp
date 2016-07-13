<? //$this->layout = 'public'; ?>

<div class="login-form-container">
    <div class="form">
        
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'users-form',
        'enableAjaxValidation'=>false,
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
            <h6 class="pull-left"><?=static::$actionsArray['recoverPassword']['title']?></h6>
        </div>
        <div class="row">
            <?=$form->errorSummary($model);?>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'password'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->passwordField($model,'password',array('class'=>'form-control direct-ltr','placeholder'=>'Password...')); ?>
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

        <div class="row">
            <div class="col-md-4 pull-left">
                <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>
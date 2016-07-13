<?
CHtml::$afterRequiredLabel = '';
CHtml::$beforeRequiredLabel = '';
//$this->layout = 'public'; ?>

<div class="login-form-container">
    <?if(Yii::app()->user->hasFlash('success')):?>
        <div style="text-align: center" class="alert alert-success col-md-8 col-md-offset-2" role="alert">
            <?=Yii::app()->user->getFlash('success')?>
        </div>
    <?else:?>
        <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-form',
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
        ));
        ?>
        <div class="row">
            <h6 class="pull-left"><?=static::$actionsArray['forgetPassword']['title']?></h6>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'email'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->textField($model,'email',array('class'=>'form-control direct-ltr','placeholder'=>'Email...')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-left">
                <?php echo CHtml::submitButton('ارسال ایمیل', array('class'=>'form-control btn btn-default submit')); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->
    <?endif;?>
</div>                     

<? $this->layout = 'public'; ?>

<div class="login-form-container">     
    رمز عبور جدید و تکرار آن را وارد کنید:
    <? $this->beginWidget('zii.widgets.CPortlet', array()); ?>
    
    <div class="form">
        
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'users-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ))); ?>

        <div class="row">                         
            <?php echo $form->passwordField($model, 'password', array('size' => 60, 'maxlength' => 100, 'class' => 'cms-input full-width', 'placeholder' => 'رمز عبور جدید', 'style' => "direction:ltr;", "value" => "")); ?>
            <span class="validate-message">
                <?php echo $form->error($model, 'password'); ?>
            </span> 
        </div>

        <div class="row">                          
            <?php echo $form->passwordField($model, 'repeat_password', array('size' => 60, 'maxlength' => 100, 'class' => 'cms-input full-width', 'placeholder' => 'تکرار رمز عبور جدید', 'style' => "direction:ltr;", "value" => "")); ?>
            <span class="validate-message">
                <?php echo $form->error($model, 'repeat_password'); ?>
            </span>
        </div>  

        <div class="row buttons">
            <?php echo CHtml::submitButton('ثبت', array('class' => 'cms-button black-button')); ?>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->
    <?php $this->endWidget(); ?>

</div>
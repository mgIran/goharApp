<? //$this->layout = 'public'; ?>

<div class="login-form-container">     

    <? $this->beginWidget('zii.widgets.CPortlet', array()); ?>

    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));
        ?>

        <div class="row">
            <?php echo $form->emailField($model, 'email', array("style" => "direction:ltr;", 'class' => 'login-input full-width', 'placeholder' => 'ایمیل')); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton('ارسال ایمیل', array('class' => 'cms-button black-button')); ?>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->
    <?php $this->endWidget(); ?>

</div>                     

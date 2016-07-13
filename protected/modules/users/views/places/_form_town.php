<?php
/* @var $this PlacesController */
/* @var $model Places */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'users-places-form',
        'enableAjaxValidation'=>true,
        'htmlOptions' => array(
            'class' => 'col-md-6 col-md-offset-4'
        ),
    )); ?>

    <div class="row">
        <h6 class="pull-left"><?=$title?></h6>
    </div>

    <div class="row errors">
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-8 pull-right">
            <?php //echo $form->errorSummary($model); ?>
            <?php echo $form->error($model,'title'); ?>
            <?php echo $form->error($model,'national_id_prefix'); ?>
            <?php echo $form->error($model,'postal_code_prefix'); ?>
            <?php echo $form->error($model,'phone_number_prefix'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'title'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'title',array('class'=>'form-control','placeholder'=>'User Name...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'national_id_prefix'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php
            $this->widget('ext.Tokenize.Tokenize', array(
                'model' => $model,
                'attribute' => 'national_id_prefix',
                'options' => array(
                    'placeholder' => 'تایپ کنید...',
                    'maxElements' => '10'
                )
            ));
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'postal_code_prefix'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php
            $this->widget('ext.Tokenize.Tokenize', array(
                'model' => $model,
                'attribute' => 'postal_code_prefix',
                'options' => array(
                    'placeholder' => 'تایپ کنید...',
                    'maxElements' => '10'
                )
            ));
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'phone_number_prefix'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php
            $this->widget('ext.Tokenize.Tokenize', array(
                'model' => $model,
                'attribute' => 'phone_number_prefix',
                'options' => array(
                    'placeholder' => 'تایپ کنید...',
                    'maxElements' => '10'
                )
            ));
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-left">
            <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
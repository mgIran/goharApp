<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'contacts-categories-form',
        'enableAjaxValidation'=>true,
        'htmlOptions' => array(
            'class' => 'col-md-6 col-md-offset-4'
        ),
    )); ?>


    <div class="row">
        <h6 class="pull-left"><?=static::$actionsArray[$this->action->id]['title']?></h6>
    </div>

    <div class="row errors">
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-8 pull-right">
            <?php echo $form->errorSummary($model); ?>
            <?php echo $form->error($model,'title'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'title'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'title',array('class'=>'form-control','placeholder'=>'Title...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-left">
            <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
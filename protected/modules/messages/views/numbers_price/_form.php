<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'messages-texts-numbers-prefix-form',
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
            <?php echo $form->error($model,'id'); ?>
            <?php echo $form->error($model,'price'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'id'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'id',array('class'=>'form-control direct-ltr','placeholder'=>'Numbers quantity...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'price'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <? $this->widget("ext.iWebFunctions.iWebFunctions");?>
            <?php echo $form->textField($model,'price',array('class'=>'form-control direct-ltr','placeholder'=>'Price...','onKeyUp' => '$(this).val(iWebFunctions.splitNumber($(this).val()));')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-left">
            <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
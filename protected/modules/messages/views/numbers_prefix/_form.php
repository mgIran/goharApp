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
            <?php echo $form->error($model,'number'); ?>
            <?php echo $form->error($model,'minimum_number'); ?>
            <?php echo $form->error($model,'maximum_number'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'number'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'number',array('class'=>'form-control direct-ltr','placeholder'=>'Number...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'minimum_number'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'minimum_number',array('class'=>'form-control direct-ltr','placeholder'=>'Minimum...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'maximum_number'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'maximum_number',array('class'=>'form-control direct-ltr','placeholder'=>'Maximum...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'status'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php
            $statusList = MessagesTextsNumbersPrefix::$statusList;
            $this->widget('ext.iWebDropDown.iWebDropDown', array(
                'model' => $model,
                'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                'label'=> $statusList[$model->status],
                'name'=>'status',
                'list'=> $statusList,
                'id' => 'MessagesTextsNumbersPrefix_status',
                'value' => $model->status
            )); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-left">
            <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
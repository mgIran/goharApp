<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'messages-emails-bank-categories-form',
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
            <?php echo $form->error($model,'parent_id'); ?>
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
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'parent_id'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                'model' => $model,
                'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                'label'=>((isset($model->parent->title))?$model->parent->title:'بدون والد'),
                'name'=>'parent_id',
                'list'=> $categories,
                'id' => 'MessagesEmailsBankCategories_parent_id',
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
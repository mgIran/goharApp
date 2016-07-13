<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'messages-texts-numbers-specials-form',
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
            <?php echo $form->error($model,'prefix_id'); ?>
            <?php echo $form->error($model,'price'); ?>
            <?php echo $form->error($model,'view'); ?>
            <?php echo $form->error($model,'status'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'prefix_id'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                'model' => $model,
                'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                'label'=> (!is_null($model->prefix_id))?$model->prefix->number:'بدون پیش شماره',
                'name'=>'prefix_id',
                'list'=> $prefixes,
                'id' => 'MessagesTextsNumbersSpecials_prefix_id',
            )); ?>
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
            <?php echo $form->labelEx($model,'price'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <? $this->widget("ext.iWebFunctions.iWebFunctions");?>
            <?php echo $form->textField($model,'price',array('class'=>'form-control direct-ltr','placeholder'=>'Price...','onKeyUp' => '$(this).val(iWebFunctions.splitNumber($(this).val()));')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'view'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'view',array(
                'class'=>'form-control direct-ltr',
                'placeholder'=>'View Of Number...',
                'data-toggle' => 'tooltip',
                'data-placement' => 'left',
                'title' => '
<div style="text-align: justify;line-height: 22px">
                برای نمایش شماره کاربرد دارد
                <br/>
                مثلا شماره
<div style="text-align:center;border:1px solid #fff;"> 300090902030 </div>
به صورت
<div style="text-align:center;border:1px solid #fff;direction: ltr"> 3000 90 90 20 30 </div>
می تواند وارد شود
<br/>
  ( پیش فرض همان شماره وارد شده می باشد )
</div>
                '
            )); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'status'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                'model' => $model,
                'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                'label'=> MessagesTextsNumbersSpecials::$statusList[$model->status],
                'name'=>'status',
                'list'=> MessagesTextsNumbersSpecials::$statusList,
                'id' => 'MessagesTextsNumbersSpecials_status',
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

<?
Yii::app()->clientScript->registerScript("viewTooltip","
    $('#MessagesTextsNumbersSpecials_view').tooltip({'html':true});
",CClientScript::POS_END);
?>
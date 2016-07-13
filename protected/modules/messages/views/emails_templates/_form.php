<?
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/ckeditor/ckeditor.js');

$form = $this->beginWidget('CActiveForm',array(
'id' => 'messages-emails-drafts',
'enableAjaxValidation' => true
));

?>
<br/>
<div class="form">
    <div class="row">
        <div class="col-md-2 pull-right"></div>
        <div class="col-md-8 pull-right">
            <h6 class="pull-right"><?=static::$actionsArray[$this->action->id]['title']?></h6>
        </div>
    </div>
    <?foreach(Yii::app()->user->getFlashes() as $key => $message):?>
        <div class="alert alert-<?=$key?> alert-dismissible col-md-8 col-md-offset-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?=$message?>
        </div>
        <div class="clearfix"></div>
    <?endforeach;?>

    <div class="row">
        <div class="col-md-2 pull-right">
            <? echo $form->labelEx($model,'title')?>
        </div>
        <div class="col-md-8 pull-right">
            <? echo $form->textField($model,'title',array(
                'class' => 'form-control',
                'maxlength' => 255,
            ));?>
            <? echo $form->error($model,'title')?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 pull-right">
            <? echo $form->labelEx($model,'template')?>
        </div>
        <div class="col-md-8 pull-right">
            <? echo $form->textArea($model,'template',array(
                'class' => 'form-control ckeditor',
                'rows' => 3,
                'maxlength' => 640,
            ));?>
            <? echo $form->error($model,'template')?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 pull-right">
            <?php echo $form->labelEx($model,'status'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                'model' => $model,
                'label'=>$model::$statusList[$model->status],
                'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                'name'=>'status',
                'list'=> $model::$statusList ,
                'id' => 'status',
                'value' => $model->status
            )); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1 col-md-offset-2" style="padding: 0">
            <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>
</div>
<?$this->endWidget();?>
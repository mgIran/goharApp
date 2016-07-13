<?
if($model->isNewRecord)
    $trackingNo = Yii::app()->user->tracking_no;
else
    $trackingNo = ($model->tracking_no)?$model->tracking_no:'?';

$label = "";
if($model->status == Buys::STATUS_DONE)
    $label = 'label label-success';
elseif($model->status == Buys::STATUS_FAILED)
    $label = 'label label-danger';
$form = $this->beginWidget('CActiveForm', array(
    'id'=>'buys-form',
));
?>
<?if($model->isNewRecord):?>
    <div class="buy-form">
        <div class="transparent-box"></div>
        <div class="login-button-container buy">
            <?php echo CHtml::submitButton('پرداخت و فعالسازی آنلاین',array('class'=>'login-button buy')); ?>
        </div>
    </div>
<?endif;?>

<div class="col-md-9<?=(!$model->isNewRecord)?' pull-right':'" style="position: absolute;left: 0'?>">
    <div class="row">
        <div class="col-md-3 pull-right">
            <?=$model->getAttributeLabel('tracking_no')?>
        </div>
        <div class="col-md-3 pull-right">
            <?=$trackingNo?>
        </div>
        <div class="col-md-3 pull-right">
            <?=$model->getAttributeLabel('status')?>
        </div>
        <div class="col-md-3 pull-right">
            <span class="<?=$label?>">
                <?=($model->status)?$model::$statusList[$model->status]:'?'?>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 pull-right">
            <?=$model->getAttributeLabel('date')?>
        </div>
        <div class="col-md-3 pull-right">
            <?=Yii::app()->jdate->date('Y/m/d',($model->date)?$model->date:time())?>
        </div>
        <div class="col-md-3 pull-right">
            <?=$model->getAttributeLabel('time')?>
        </div>
        <div class="col-md-3 pull-right">
            <?=Yii::app()->jdate->date('H:i',($model->date)?$model->date:time())?>
        </div>
    </div>
</div>
<?php
    echo $form->hiddenField($model,'details',array('value'=>json_encode($factorFields)));
    echo $form->hiddenField($model,'qty');
$this->endWidget(); ?>
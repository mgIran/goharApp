<?
$label = "";
if($buyModel->isNewRecord)
    $trackingNo = Yii::app()->user->tracking_no;
else
{
    $trackingNo = ($buyModel->buy->tracking_no)?$buyModel->buy->tracking_no:'?';
    if($buyModel->buy->status == Buys::STATUS_DONE)
        $label = 'label label-success';
    elseif($buyModel->buy->status == Buys::STATUS_FAILED)
        $label = 'label label-danger';
}


$form = $this->beginWidget('CActiveForm', array(
    'id'=>'plans-buys-form',
    //'enableAjaxValidation'=>true,
));
?>
<?if($buyModel->isNewRecord):?>
    <div class="buy-form">
        <div class="transparent-box"></div>
        <div class="login-button-container buy">
            <?php echo CHtml::submitButton('پرداخت و فعالسازی آنلاین',array('class'=>'login-button buy')); ?>
        </div>
    </div>
<?endif;?>

<div class="col-md-9<?=(!$buyModel->isNewRecord)?' pull-right':'" style="position: absolute;left: 0'?>">
    <div class="row">
        <div class="col-md-3 pull-right">
            <?=$buyModel->getAttributeLabel('tracking_no')?>
        </div>
        <div class="col-md-3 pull-right">
            <?=$trackingNo?>
        </div>
        <div class="col-md-3 pull-right">
            <?=$buyModel->getAttributeLabel('status')?>
        </div>
        <div class="col-md-3 pull-right">
            <span class="<?=$label?>">
                <?=(isset($buyModel->buy->status))?Buys::$statusList[$buyModel->buy->status]:'?'?>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 pull-right">
            <?=$buyModel->getAttributeLabel('date')?>
        </div>
        <div class="col-md-3 pull-right">
            <?=Yii::app()->jdate->date('Y/m/d',(isset($buyModel->buy->date))?$buyModel->buy->date:time())?>
        </div>
        <div class="col-md-3 pull-right">
            <?=$buyModel->getAttributeLabel('time')?>
        </div>
        <div class="col-md-3 pull-right">
            <?=Yii::app()->jdate->date('H:i',(isset($buyModel->buy->date))?$buyModel->buy->date:time())?>
        </div>
    </div>
</div>
<?php
    echo $form->hiddenField($buyModel,'details',array('value'=>json_encode($factorFields)));
    echo $form->hiddenField($buyModel,'charge_kind');

$this->endWidget(); ?>
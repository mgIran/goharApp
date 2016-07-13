<?
if($buyModel->isNewRecord)
    $trackingNo = Yii::app()->user->tracking_no;
else
    $trackingNo = ($buyModel->tracking_no)?$buyModel->tracking_no:'?';

$label = "";
if($buyModel->status == Buys::STATUS_DONE)
    $label = 'label label-success';
elseif($buyModel->status == Buys::STATUS_FAILED)
    $label = 'label label-danger';
$form = $this->beginWidget('CActiveForm', array(
    'id'=>'messages-texts-buy-form',
    //'enableAjaxValidation'=>true,
));
?>
<?if($buyModel->isNewRecord OR $buyModel->status == Buys::STATUS_DOING):?>
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
                <?=($buyModel->status)?$buyModel::$statusList[$buyModel->status]:'?'?>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 pull-right">
            <?=$buyModel->getAttributeLabel('date')?>
        </div>
        <div class="col-md-3 pull-right">
            <?=Yii::app()->jdate->date('Y/m/d',($buyModel->date)?$buyModel->date:time())?>
        </div>
        <div class="col-md-3 pull-right">
            <?=$buyModel->getAttributeLabel('time')?>
        </div>
        <div class="col-md-3 pull-right">
            <?=Yii::app()->jdate->date('H:i',($buyModel->date)?$buyModel->date:time())?>
        </div>
    </div>
</div>
<?php
    echo $form->hiddenField($buyModel,'details',array('value'=>json_encode($factorFields)));
//echo $form->hiddenField($buyModel,'qty',array('value'=>$numberOfPages));

$this->endWidget(); ?>
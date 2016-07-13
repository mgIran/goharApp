<div class="plans-labels">
    <?
    $form = $this->beginWidget('CActiveForm', array(
        'id'=>'buys-form',
        'action' => Yii::app()->createAbsoluteUrl('financial/checkouts/buy/?buyId='.$buy->id)
    ));
    ?>
    <br/>
    <div class="row">
        <div class="col-md-2 pull-right"></div>
        <div class="col-md-2 pull-right">
            <? echo $form->labelEx($buy,'status',array('style'=>'color:#fff;float:left'))?>
        </div>
        <div class="col-md-4 pull-right">
            <?echo $form->dropDownList($buy,'status',Buys::$statusList);?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 pull-right">
        </div>
        <div class="col-md-2 pull-right">
            <? echo CHtml::label('رمز','pass',array('style'=>'color:#fff;float:left'))?>
        </div>
        <div class="col-md-4 pull-right">
            <?echo CHtml::textField('pass','',array('id'=>'pass'))?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-2 pull-right"></div>
        <div class="col-md-2 pull-right"></div>
        <div class="col-md-4 pull-right">
            <?php
            echo $form->hiddenField($buy,'gateway',array('value'=>'ملی'));
            echo $form->hiddenField($buy,'tracking_no',array('value'=>Yii::app()->user->tracking_no));
            echo CHtml::submitButton('ثبت',array('class'=>'btn btn-default')); ?>
        </div>
    </div>
    <br/>
    <?$this->endWidget(); ?>
</div>
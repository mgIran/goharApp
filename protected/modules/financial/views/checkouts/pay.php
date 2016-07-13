<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'ajax-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array(
            'class' => 'col-md-6 col-md-offset-2'
        )
    )); ?>
    <? if(($flashMessage = Yii::app()->user->getFlash('success')) !== null):?>    <div class="alert alert-success">
        <i class="fa fa-check-square-o fa-lg"></i>
        <?=$flashMessage;?>    </div>
    <? endif;?><? if(($flashMessage = Yii::app()->user->getFlash('info')) !== null):?>    <div class="alert alert-info">
        <i class="fa fa-info-circle fa-lg"></i>
        <?=$flashMessage;?>    </div>
    <? endif;?><? if(($flashMessage = Yii::app()->user->getFlash('danger')) !== null):?>    <div class="alert alert-danger">
        <i class="fa fa-frown-o fa-lg"></i>
        <?=$flashMessage;?>    </div>
    <? endif;?>
    <?if(isset($title)):?>
        <div class="row">
            <h6 class="pull-left"><?=$title?></h6>
        </div>
    <?endif;?>

    <div class="row">
        <div class="col-md-4 pull-right">
            <label>
                مبلغ درخواستی
            </label>
        </div>
        <div class="col-md-8 unity">
            <? echo number_format(ceil($model->price * 100 / floatval(100 + $model->wage))); ?>
            تومان
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 pull-right">
            <label>
                کارمزد
            </label>
        </div>
        <div class="col-md-8 unity">
            <? echo $model->wage; ?>
            درصد
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 pull-right">
            <label>
                مبلغ صورتحساب
            </label>
        </div>
        <div class="col-md-8 unity">
            <? echo number_format($model->price); ?>
            تومان
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'tracking_no'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'tracking_no',array('class'=>'form-control direct-ltr','placeholder'=>'Tracking Number')); ?>
        </div>
        <?php echo $form->error($model,'tracking_no'); ?>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'gateway'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'gateway',array('class'=>'form-control direct-ltr','placeholder'=>'Gateway')); ?>
        </div>
        <?php echo $form->error($model,'gateway'); ?>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'pay_date'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?
            $this->widget('ext.JalaliDatePicker.JalaliDatePicker',array('textField'=>'pay_date',
                'options'=>array(
                    'changeMonth'=>'true',
                    'changeYear'=>'true',
                    'showButtonPanel'=>'true',
                    'changeDate' => 'true',
                ),
                'model' => $model
            ));

            $this->widget('ext.timepicker.timepicker', array(
                'model'=>$model,
                'name'=>'pay_date',
                'skin' => 'new',
                'options' => array(
                    'htmlOptions' => array('placeholder'=>'Pay Date...')
                )
            ));

            ?>
            <?php echo $form->error($model,'pay_date'); ?>
        </div>

    </div>

    <div class="row" style="margin-top: 24px;padding: 0">
        <div class="col-md-4 pull-left">
            <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
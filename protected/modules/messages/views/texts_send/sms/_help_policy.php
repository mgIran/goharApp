<div class="help-policy">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'sending-sms-l1',
        'enableAjaxValidation' => true
    ));

    Yii::app()->clientScript->registerScript('selectCheckbox', "
        $('.accept-checkbox').change(function(){
            if(!$(this).is(':checked'))
                $('#next-step').prop('disabled', true);
            else
                $('#next-step').prop('disabled', false);
        });
        $('#next-step').on('click', function(){
            window.location.href='".$nextStepUrl."';
        });
    ");
    ?>
    <div class="form">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="panel panel-body col-md-8">
                <?php if(!is_null($helpPolicy) AND !empty($helpPolicy)):?>
                    <?=$helpPolicy->text;?>
                <?php endif;?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">سیستم های ارسالی :</div>
            <div class="col-md-8"><?=$sendingSystem->value;?></div>
        </div>
        <div class="row">
            <div class="col-md-2">کاربرد این نوع ارسال:</div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-8">
                        <?php echo CHtml::radioButton('send_usage_1', true, array(
                            'class' => 'pull-right',
                            'id' => 'send_usage_1'
                        ));?>
                        <?php echo CHtml::label('الف) '.$sendUsageA->value, 'send_usage_1', array(
                            'class' => 'pull-right',
                        ));?>
                    </div>
                    <div class="col-md-8">
                        <?php echo CHtml::radioButton('send_usage_2', false, array(
                            'class' => 'pull-right',
                            'id' => 'send_usage_2',
                            'disabled' => true
                        ));?>
                        <?php echo CHtml::label('ب) '.$sendUsageB->value, 'send_usage_2', array(
                            'class' => 'pull-right',
                        ));?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <?php echo CHtml::label('مطالب فوق را قبول دارم.', 'accept-checkbox', array(
                    'class' => 'pull-left',
                ));?>
                <?php echo CHtml::checkBox('accept', false, array(
                    'class' => 'accept-checkbox pull-left',
                    'id' => 'accept-checkbox'
                ));?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 pull-left">
                <?php echo CHtml::button('مرحله بعد', array(
                    'class' => 'form-control btn btn-default submit',
                    'id'=>'next-step',
                    'disabled' => true
                ));?>
            </div>
        </div>
    </div>
    <?php $this->endWidget();?>
</div>
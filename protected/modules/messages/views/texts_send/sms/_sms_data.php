<div class="sms-data">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'sending-sms-l2',
        'action'=>$nextStepUrl,
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true
        )
    ));
    Yii::app()->clientScript->registerCss('general', '
        .prepared-text span
        {
            display: inline-block;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 10px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        .prepared-text span:hover
        {
            color: #f00;
        }
        #Sms_message_type label
        {
            float: none;
        }
        .test-send-error,
        .save-sms-error
        {
            display: none;
            color: #f00;
        }
    ');
    Yii::app()->clientScript->registerScript('selectSendType', "
        var statusCheckInterval;
        $('input[type=\"radio\"][name=\"Sms[send_type]\"]').change(function(){
            if($(this).is(':checked') && $(this).attr('id') == 'Sms_send_type_2')
                $('#Sms_attachment_1_title, #Sms_attachment_1_url, #Sms_attachment_2_title, #Sms_attachment_2_url').prop('disabled', false);
            else
                $('#Sms_attachment_1_title, #Sms_attachment_1_url, #Sms_attachment_2_title, #Sms_attachment_2_url').prop('disabled', true);
        });
    ");
    Yii::app()->clientScript->registerScript('checkContextType', "
        $('#Sms_body').on('keyup', function(){
            if($(this).val().length > 0)
                $('#btn-test-send, #btn-save, #next-step').prop('disabled', false);
            else
                $('#btn-test-send, #btn-save, #next-step').prop('disabled', true);

            if(getContextType($(this).val()) == 'Farsi')
            {
                $('.context-type').text('فارسی');
                $('#Sms_context_type').val('farsi');
            }
            else
            {
                $('.context-type').text('لاتین');
                $('#Sms_context_type').val('latin');
            }
        });
    ");
    Yii::app()->clientScript->registerScript('addPreparedText', "
        $('.prepared-text span').on('click', function(){
            $('#Sms_body').val($('#Sms_body').val() + $(this).data('value'));
        });
    ");
    Yii::app()->clientScript->registerScript('smsPaging', "
        $('#Sms_body').on('keyup', function(){
            var textLength = $(this).val().length,
                pageNumber = 1,
                pageSize = 0,
                temp;

            if(textLength <= 70)
                pageNumber = 1;
            else
            {
                temp = textLength - 70;
                pageNumber = Math.ceil(temp / 66) + 1;
            }

            if(pageNumber == 1)
                pageSize = 70;
            else
            {
                pageSize = 66;
                temp = textLength - 70;
                textLength = temp - (Math.floor(temp / 66) * 66);
            }

            $('#Sms_body_remaining_character_count').text((textLength == 0) ? 0 : (pageSize - textLength));
            $('#Sms_body_pages_count').text(pageNumber);
        });
    ");
    ?>
    <div class="form">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 panel panel-body">
                <?php if(!is_null($helpPolicy) AND !empty($helpPolicy)):?>
                    <?=$helpPolicy->text;?>
                <?php endif;?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <? echo $form->labelEx($model,'title')?>
            </div>
            <div class="col-md-8">
                <?php echo $form->textField($model, 'title', array('class' => 'form-control'));?>
                <?php echo $form->error($model, 'title');?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <? echo $form->labelEx($model,'body')?>
            </div>
            <div class="col-md-8">
                <?php echo $form->textArea($model, 'body', array('class' => 'form-control'));?>
                <?php echo $form->error($model, 'body');?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-6">
                <div class="prepared-text">
                    افزودن آیتم اختصاصی:
                    <span data-value="#NAME#">نام</span>
                    <span data-value="#FAMILY#">نام خانوادگی</span>
                    <span data-value="#GENDER#">جنسیت</span>
                    <span data-value="#PHONE_NUMBER#">شماره موبایل</span>
                    <span data-value="#EMAIL#">پست الکترونیکی</span>
                </div>
            </div>
            <div class="col-md-2" style="font-size: 12px;">
                <?php echo CHtml::label('حرف باقیمانده', '');?>
                <?php echo CHtml::label('70', '', array('id' => 'Sms_body_remaining_character_count'));?>
                <?php echo CHtml::label('/', '');?>
                <?php echo CHtml::label('صفحه', '');?>
                <?php echo CHtml::label('0', '', array('id' => 'Sms_body_pages_count'));?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <span class="context-type pull-left">فارسی</span>
                <?php echo $form->hiddenField($model, 'context_type');?>
                <?php echo $form->labelEx($model, 'context_type');?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8"><hr></div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?php echo $form->labelEx($model, 'sender_id');?>
            </div>
            <div class="col-md-8">
                <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                    'label'=> $model->sender_id,
                    'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                    'name'=>'Sms[sender_id]',
                    'list'=> CHtml::listData($senders,'id','number') ,
                    'id'=>'Sms_sender_id',
                )); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?php echo $form->labelEx($model, 'send_type');?>
            </div>
            <div class="col-md-8">
                <?php echo CHtml::radioButton('Sms[message_type]', true, array(
                    'class' => 'pull-right',
                    'value' => 'sms'
                ));?>
                <?php echo CHtml::label('SMS' ,'Sms_message_type', array(
                    'class' => 'pull-right'
                ));?>
                <?php echo CHtml::radioButton('message_type_flash', false, array(
                    'class' => 'pull-right',
                    'disabled' => 'true'
                ));?>
                <?php echo CHtml::label('Flash' ,'message_type_flash', array(
                    'class' => 'pull-right'
                ));?>
                <?php echo CHtml::radioButton('message_type_wappush', false, array(
                    'class' => 'pull-right',
                    'disabled' => 'true'
                ));?>
                <?php echo CHtml::label('WAP Push' ,'message_type_wappush', array(
                    'class' => 'pull-right'
                ));?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?php echo $form->labelEx($model, 'attachment_1_title');?>
            </div>
            <div class="col-md-3">
                <?php echo $form->textField($model, 'attachment_1_title', array(
                    'class' => 'form-control',
                    'disabled' => 'true'
                ));?>
                <?php echo $form->error($model, 'attachment_1_title');?>
            </div>
            <div class="col-md-2">
                <?php echo $form->labelEx($model, 'attachment_1_url');?>
            </div>
            <div class="col-md-3">
                <?php echo $form->textField($model, 'attachment_1_url', array(
                    'class' => 'form-control',
                    'disabled' => 'true'
                ));?>
                <?php echo $form->error($model, 'attachment_1_url');?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?php echo $form->labelEx($model, 'attachment_2_title');?>
            </div>
            <div class="col-md-3">
                <?php echo $form->textField($model, 'attachment_2_title', array(
                    'class' => 'form-control',
                    'disabled' => 'true'
                ));?>
                <?php echo $form->error($model, 'attachment_2_title');?>
            </div>
            <div class="col-md-2">
                <?php echo $form->labelEx($model, 'attachment_2_url');?>
            </div>
            <div class="col-md-3">
                <?php echo $form->textField($model, 'attachment_2_url', array(
                    'class' => 'form-control',
                    'disabled' => 'true'
                ));?>
                <?php echo $form->error($model, 'attachment_2_url');?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8"><hr></div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?php echo CHtml::label('تست ارسال', '');?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?php echo CHtml::label('شماره موبایل گیرنده', 'test-contact');?>
            </div>
            <div class="col-md-8">
                <?php echo CHtml::textField('test-contact', '', array(
                    'class' => 'form-control'
                ));?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <div class="test-send-error"></div>
            </div>
            <div class="col-md-2 pull-left">
                <?php echo CHtml::ajaxButton('ارسال', CController::createUrl('texts_send/test/' . $sendtype), array(
                    'type' => 'POST',
                    'dataType' => 'JSON',
                    'beforeSend' => "function(){
                        if($('input#Sms_sender_id').val() == '')
                        {
                            $('.test-send-error').text('لطفا فرستنده را مشخص کنید').show();
                            return false;
                        }
                        if($('#test-contact').val() == '')
                        {
                            $('.test-send-error').text('لطفا شماره موبایل گیرنده را وارد کنید').show();
                            return false;
                        }
                        else if(!(/[0-9]{11}/.test($('#test-contact').val())))
                        {
                            $('.test-send-error').text('شماره موبایل وارد شده اشتباه است').show();
                            return false;
                        }
                        $('.test-send-error').hide();
                    }",
                    'success' => "function(data){
                        $('#test-send-sms-result ul').html('');
                        if(data.hasError)
                        {
                            switch(data.errorSection)
                            {
                                case 'allowAdminToSendSMS':
                                    $('#test-send-sms-result ul').append('<li>' + data.allowAdminToSendSMS + '</li>');
                                    break;

                                case 'sendTimeRange':
                                    $('#test-send-sms-result ul').append('<li>' + data.sendTimeRange + '</li>');
                                    break;

                                case 'SMSCharge':
                                    $('#test-send-sms-result ul').append('<li>' + data.sendTimeRange + '</li>');
                                    $('#test-send-sms-result ul').append('<li>' + data.SMSCharge + '</li>');
                                    break;

                                case 'sendingRequest':
                                    $('#test-send-sms-result ul').append('<li>' + data.sendTimeRange + '</li>');
                                    $('#test-send-sms-result ul').append('<li>' + data.SMSCharge + '</li>');
                                    $('#test-send-sms-result ul').append('<li>' + data.sendingRequest.message + '</li>');
                                    break;
                            }
                        }
                        else
                        {
                            $('#test-send-sms-result ul').append('<li>' + data.sendTimeRange + '</li>');
                            $('#test-send-sms-result ul').append('<li>' + data.SMSCharge + '</li>');
                            $('#test-send-sms-result ul').append('<li>' + data.sendingRequest.message + '</li>');
                            $('#test-send-sms-result ul').append('<li></li>');
                            $('#test-send-sms-result ul').append('<li>' + data.sendingRequest.WSResponse.message + '</li>');
                            if(data.sendingRequest.WSResponse.needToFollow)
                                statusCheckInterval=setInterval(function(){ checkSmsStatus(data.sendingRequest.smsID) }, 30000);
                        }
                    }"
                ), array(
                    'id' => 'btn-test-send',
                    'class' => 'form-control btn btn-default submit',
                    'disabled' => 'true'
                ));?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div id="test-send-sms-result">
                    <ul></ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8"><hr></div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <div class="save-sms-error"></div>
                <div class="save-sms-message"></div>
            </div>
            <div class="col-md-2 pull-left">
                <?php echo CHtml::submitButton('مرحله بعد', array(
                    'id' => 'next-step',
                    'class' => 'form-control btn btn-default submit',
                    'disabled' => 'true'
                ));?>
            </div>
            <div class="col-md-2 pull-left">
                <?php echo CHtml::hiddenField('sid',null);?>
                <?php echo CHtml::hiddenField('saved','false');?>
                <?php echo CHtml::ajaxSubmitButton('ذخیره', CController::createUrl('texts_send/save'), array(
                    'type' => 'POST',
                    'data'=>"js:$('#sending-sms-l2').serialize()",
                    'dataType'=>'JSON',
                    'success'=>"function(data){
                        if(data.hasError)
                        {
                            var settings = $('#sending-sms-l2').data('settings');
                            $.each(settings.attributes, function () {
                                this.status = 2; // force ajax validation
                            });
                            $('#sending-sms-l2').data('settings', settings);

                            // trigger ajax validation
                            $.fn.yiiactiveform.validate($('#sending-sms-l2'), function (data) {
                                $.each(settings.attributes, function () {
                                    $.fn.yiiactiveform.updateInput(this, data, $('#sending-sms-l2'));
                                });
                            });
                            $('.save-sms-error').text('لطفا خطاهای موجود را برطرف نمایید.').show();
                        }
                        else
                        {
                            if(data.status=='success')
                            {
                                $('.save-sms-message').text(data.message);
                                $('#sid').val(data.sid);
                                $('#saved').val('true');
                            }
                            else
                                $('.save-sms-error').text(data.message).show();
                        }
                    }",
                ), array(
                    'id' => 'btn-save',
                    'class' => 'form-control btn btn-default submit',
                    'disabled' => 'true'
                ));?>
            </div>
        </div>
    </div>
    <?php $this->endWidget();?>
</div>
<?php Yii::app()->clientScript->registerScript('functions', "
    function getContextType(context)
    {
        var farsiChars = ['ا', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م', 'ن', 'و', 'ه', 'ی', 'آ', 'ة', 'ي', 'ؤ', 'إ', 'أ', 'ء', 'ئ', 'َ', 'ُ', 'ِ', 'ّ', 'ۀ', 'ً', 'ٌ', 'ٍ'];
        var neutralChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '+', '-', '*', '/', '.', ',', '\\\', '\\`', '=', '[', ']', '\\'', ';', '<', '>', '?', ':', '\\\"', '|', '{', '}', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '×', ' '];
        var farsiCharsCount = 0,
            latinCharsCount = 0;

        for(var i = 0; i < context.length; i++)
        {
            if(neutralChars.indexOf(context[i]) != -1)
                continue;

            if(farsiChars.indexOf(context[i]) == -1)
                latinCharsCount++;
            else
                farsiCharsCount++;
        }

        if(latinCharsCount == farsiCharsCount)
            return 'Farsi';
        else if(latinCharsCount > farsiCharsCount)
            return 'Latin';
        else if(latinCharsCount < farsiCharsCount)
            return 'Farsi';
    }

    function checkSmsStatus(smsID)
    {
        $.ajax({
            'type':'POST',
            'dataType':'JSON',
            'url':'/gohar/messages/texts_send/getSmsStatus/rfs',
            'cache':false,
            'data':{'smsID':smsID},
            'success':function(data){
                $('#test-send-sms-result ul').append('<li>' + data.sendingRequest.WSResponse.message + '</li>');
                if(!data.sendingRequest.WSResponse.needToFollow)
                    clearInterval(statusCheckInterval);
            }
        });
    }
");?>
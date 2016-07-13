<div class="container-fluid">
    <div style="padding: 15px 0">
        <?php echo CHtml::textArea('manual-recipients', '', array('class'=>'form-control'));?>
        <?php echo CHtml::ajaxButton('ثبت', Yii::app()->createUrl('/messages/texts_send/addManuallyRecipients'), array(
            'type'=>'POST',
            'dataType'=>'JSON',
            'data'=>"js:{recipients:$('#manual-recipients').val(),sid:$('#sid').val()}",
            'beforeSend'=>"js:function(){if($('#manual-recipients').val()==''){alert('لطفا شماره گیرندگان را وارد کنید!');return false;}}",
            'success'=>"function(data){
                if(data.status)
                {
                    $('#manual-recipients').val('');
                    alert('اطلاعات با موفقیت ثبت شد.');
                }
                else
                    alert('در ثبت اطلاعات خطایی رخ داده است لطفا مجددا تلاش کنید.');
            }"
        ), array('class'=>'btn btn-success'))?>
        <span class="desc">شماره موبایل ها توسط کارکتر کاما « , » از یکدیگر جدا شوند.</span>
        <?php Yii::app()->clientScript->registerScript('manuallyRecipientsControl',"
            $('#manual-recipients').on('keydown', function(e){
                var chs=Array('0','1','2','3','4','5','6','7','8','9',',','Backspace');
                if($.inArray(e.key, chs)==-1)
                    return false;
            });
        ");?>
    </div>
</div>
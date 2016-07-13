<div id="minimum-loading" class="factor-loading title col-md-2 pull-left" style="padding:5px 15px">در حال بروز رسانی</div>
<?
$value = intval($settings['minimum_credit']['value']);
$sign = '+';
if($value < 0) {
    $value *= -1;
    $sign = '-';
}

echo CHtml::label('
        حداقل مبلغ جهت فعالسازی "درخواست تسویه حساب" مبلغ
        ','minimum_credit',array('class'=>'pull-right'));
echo CHtml::button('ثبت',array(
    'class' => 'btn btn-default pull-right',
    'id' => 'minimum_credit_register',
    'style' => 'font-size: 17px;font-weight: bold;padding: 4px 10px;text-align: center;margin-left:10px;margin-top:-7px',
    'onClick' => CHtml::ajax(array(
        'url' => 'custom',
        'data' => 'js:{name:"minimum",value:$("#minimum_credit_sign").val()+$("#minimum_credit").val()}',
        'beforeSend' => 'js:function(){
            $("#minimum-loading").addClass("active");
        }',
        'complete' => 'js:function(){
                $("#minimum-loading").removeClass("active");
        }',
    )),
));
echo CHtml::textField("minimum_credit",number_format($value),array(
    'class'=>'form-control direct-ltr pull-right',
    'style' => 'width:116px;margin-left:10px;margin-top:-7px;padding:0 10px',
    'onKeyUp' => '$(this).val(iWebFunctions.splitNumber($(this).val()));',
));

echo CHtml::button($sign,array(
    'class' => 'btn btn-default pull-right',
    'id' => 'minimum_credit_sign',
    'style' => 'font-size: 17px;font-weight: bold;padding: 4px 10px;text-align: center;width: 35px;margin-left:10px;margin-top:-7px',
));

echo CHtml::label('
        تومان است.
        ','minimum_credit',array('class'=>'pull-right'));


Yii::app()->clientScript->registerScript("minimumCredit","
    $(document).on('click','#minimum_credit_sign',function(e){
		$(this).val(($(this).val() == '+')?'-':'+');
		$('#custom_change').focus();
	});
");
?>


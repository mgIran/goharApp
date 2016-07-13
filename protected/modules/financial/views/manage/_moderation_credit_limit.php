<div id="moderation-loading" class="factor-loading title col-md-2 pull-left" style="padding:5px 15px">در حال بروز رسانی</div>
<?
$value = intval($settings['moderation_credit_limit']['value']);
$sign = '+';
if($value < 0) {
    $value *= -1;
    $sign = '-';
}

echo CHtml::label('
        حد اعتدال اعتبار نقدی،برابر است با مجموع اعتبار نقدی کل کاربرانی که اعتبار نقدی آنها از
        ','moderation_credit_limit',array('class'=>'pull-right'));
echo CHtml::button('ثبت',array(
    'class' => 'btn btn-default pull-right',
    'id' => 'moderation_credit_limit_register',
    'style' => 'font-size: 17px;font-weight: bold;padding: 4px 10px;text-align: center;margin-left:10px;margin-top:-7px',
    'onClick' => CHtml::ajax(array(
        'url' => 'custom',
        'data' => 'js:{name:"moderation",value:$("#moderation_credit_limit_sign").val()+$("#moderation_credit_limit").val()}',
        'beforeSend' => 'js:function(){
                        $("#moderation-loading").addClass("active");
                    }',
        'complete' => 'js:function(){
                        $("#moderation-loading").removeClass("active");
                }',
    )),
));
echo CHtml::textField("moderation_credit_limit",number_format($value),array(
    'class'=>'form-control direct-ltr pull-right',
    'style' => 'width:116px;margin-left:10px;margin-top:-7px;padding:0 10px',
    'onKeyup' => '$(this).val(iWebFunctions.splitNumber($(this).val()));',
));

echo CHtml::button($sign,array(
    'class' => 'btn btn-default pull-right',
    'id' => 'moderation_credit_limit_sign',
    'style' => 'font-size: 17px;font-weight: bold;padding: 4px 10px;text-align: center;width: 35px;margin-left:10px;margin-top:-7px',
));

echo CHtml::label('
        تومان بیشتر است.
        ','moderation_credit_limit',array('class'=>'pull-right'));


Yii::app()->clientScript->registerScript("moderationCredit","
    $(document).on('click','#moderation_credit_limit_sign',function(e){
		$(this).val(($(this).val() == '+')?'-':'+');
		$('#custom_change').focus();
	});
");
?>


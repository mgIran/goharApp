<?
$price = intval(str_replace(',','',$model->price));

$plan = $this->currentUser->activePlan->plansBuys->plan;
$discountSections = json_decode($plan->extension_discount_sections,TRUE);
if(isset($discountSections['credits_buy']) AND $discountSections['credits_buy']){
    $wage = floatval($plan->extension_discount);
    $sumPrice = $price + ceil($price * ($wage / 100));
}
else {
    $wage = 0;
    $sumPrice = $price;
}

$sumPrice = $price + ceil($price * floatval($wage / 100));
?>
<div class="form col-md-12 pull-right" style="padding-bottom: 50px">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'credit-charge-form',
        'enableAjaxValidation'=>true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    )); ?>
    <div class="row">
        <h6 class="pull-right" style="padding-right: 15px">
            افزایش آنلاین اعتبار نقدی
        </h6>
    </div>

    <div class="row">
        <div class="col-md-10">
            <?php echo $form->labelEx($model,'descriptions',array('class'=>'pull-right')); ?>
        </div>
        <div class="col-md-10">
            <?php echo $form->textArea($model,'descriptions',array('class'=>'form-control','placeholder'=>'Descriptions...')); ?>
            <?php echo $form->error($model,'descriptions'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php echo $form->labelEx($model,'price'); ?>
        </div>
        <div class="col-md-6">
            <?php echo $form->textField($model,'price',array('class'=>'form-control direct-ltr','placeholder'=>'Price...')); ?>
        </div>
        <div class="col-md-2 unity">
            تومان
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php echo CHtml::label("کارمزد",'CreditsTransactions_wage'); ?>
        </div>
        <div class="col-md-6">
            <?php echo CHtml::textField('CreditsTransactions_wage',$wage,array('class'=>'form-control direct-ltr','readonly'=>'readonly')); ?>
        </div>
        <div class="col-md-2 unity">
درصد
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php echo CHtml::label("مبلغ صورتحساب",'CreditsTransactions_sum_price'); ?>
        </div>
        <div class="col-md-6">
            <?php echo CHtml::textField('CreditsTransactions_sum_price',number_format($sumPrice),array('class'=>'form-control direct-ltr','readonly'=>'readonly')); ?>
        </div>
        <div class="col-md-2 unity">
تومان
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-4">
            <?php echo $form->error($model,'price'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-6 col-md-4">
            <?php echo CHtml::submitButton('پرداخت آنلاین', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
<?
$this->widget('ext.iWebFunctions.iWebFunctions');
Yii::app()->clientScript->registerScript("CreditsTransactions_price","
$(document).on('keyup','#CreditsTransactions_price',function(){
    var price = $(this).val().replace(/,/g,'');
    $(this).val(iWebFunctions.splitNumber($(this).val()));
    var wage = $('#CreditsTransactions_wage').val();
    var sumPrice = parseInt(price) + parseInt(price * (wage / 100));
    $('#CreditsTransactions_sum_price').val(iWebFunctions.splitNumber(sumPrice.toString()));
});
");

?>

<div class="form col-md-12 pull-right" style="padding-bottom: 50px">
    <?if(is_null($model)):
        $model = new Checkouts;
        ?>
        <div class="form-fade"></div>
    <?elseif(0):?>
        <div class="alert alert-success">
            <i class="fa fa-check-square-o fa-lg"></i>
            درخواست تسویه حساب شما ثبت شده است.
        </div>
    <?endif;?>
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'checkouts-form',
        'enableAjaxValidation'=>true,
        'clientOptions' => array(
            'validateOnSubmit' => true
        ),
    )); ?>

        <div class="row">
            <h6 class="pull-right" style="padding-right: 15px">
                درخواست تسویه حساب
            </h6>
        </div>

        <div class="row">
            <div class="col-md-4">
                <?php echo $form->labelEx($model,'reqPrice'); ?>
            </div>
            <div class="col-md-6">
                <?php echo $form->textField($model,'reqPrice',array('class'=>'form-control direct-ltr','placeholder'=>'Requested Price...')); ?>
            </div>
            <div class="col-md-2 unity">
                تومان
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <?php echo $form->labelEx($model,'wage'); ?>
            </div>
            <div class="col-md-6">
                <?php echo $form->textField($model,'wage',array('class'=>'form-control direct-ltr','readonly'=>'readonly','placeholder'=>'Price...')); ?>
            </div>
            <div class="col-md-2 unity">
    درصد
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <?php echo $form->labelEx($model,'price'); ?>
            </div>
            <div class="col-md-6">
                <?php echo $form->textField($model,'price',array('class'=>'form-control direct-ltr','readonly'=>'readonly','placeholder'=>'Price...')); ?>
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
                <?php echo CHtml::submitButton('ارسال درخواست', array('class'=>'form-control btn btn-default submit')); ?>
            </div>
        </div>
    <?php $this->endWidget(); ?>
</div>

<?
$this->widget('ext.iWebFunctions.iWebFunctions');
Yii::app()->clientScript->registerScript("Checkouts_price","
$(document).on('keyup','#Checkouts_reqPrice',function(){
    var price = $(this).val().replace(/,/g,'');
    $(this).val(iWebFunctions.splitNumber($(this).val()));
    var wage = $('#Checkouts_wage').val();
    var sumPrice = parseInt(price) + parseInt(price * (wage / 100));
    $('#Checkouts_price').val(iWebFunctions.splitNumber(sumPrice.toString()));
});
");

?>
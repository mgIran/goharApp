<?$this->widget('ext.iWebFunctions.iWebFunctions');?>
<div class="form col-md-6 pull-left" style="padding-bottom: 50px">
    <?if(@in_array($lastCheckout->status,array(Checkouts::STATUS_REQUESTED,Checkouts::STATUS_DOING))):?>
        <div class="form-fade"></div>
    <?endif;?>
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'bank-details-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation' => true,
        /*'clientOptions' => array(
            'validateOnSubmit' => true,
            'afterValidate' => 'js:function(){

            }'
        ),*/
    )); ?>
    <div class="row">
        <h6 class="pull-right">
            اطلاعات حساب بانکی
        </h6>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'account_number'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'account_number',array('class'=>'form-control just-number','placeholder'=>'Account Number...')); ?>
            <?php echo $form->error($model,'account_number'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'card_number'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'card_number',array('class'=>'form-control just-number','placeholder'=>'Card Number...')); ?>
            <?php echo $form->error($model,'card_number'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'iban'); ?>
        </div>
        <div class="col-md-7 pull-right">
            <?php echo $form->textField($model,'iban',array('class'=>'form-control just-number','placeholder'=>'IBAN...','maxlength'=>24)); ?>
            <?php echo $form->error($model,'iban'); ?>
        </div>
        <div class="col-md-1 unity">
            IR
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'holder_name'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'holder_name',array('class'=>'form-control','placeholder'=>'Holder Name...','onKeyUp' => '$(this).val(iWebFunctions.filterPersian($(this).val()))')); ?>
            <?php echo $form->error($model,'holder_name'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'bank_name'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'bank_name',array('class'=>'form-control just-letter','placeholder'=>'Bank...'/*,'onKeyUp' => '$(this).val(iWebFunctions.filterPersian($(this).val()))'*/)); ?>
            <?php echo $form->error($model,'bank_name'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-left">
            <?php echo CHtml::submitButton('ویرایش', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
    <div class="row">
        <div class="col-md-12">
            <? echo Pages::getPageByName('bank_info',TRUE)?>
        </div>
    </div>
</div>
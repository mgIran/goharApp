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
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'users-bank-details-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation' => true,
        'htmlOptions' => array(
            'class' => 'col-md-6 col-md-offset-4'
        ),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        )
    )); ?>
        <div class="row">
            <h6 class="pull-left">
                مشخصات حساب بانکی
            </h6>
        </div>

        <div class="row errors">
            <div class="col-md-4 pull-right"></div>
            <div class="col-md-8 pull-right">
                <?php echo $form->errorSummary($model); ?>
                <?php echo $form->error($model,'account_number'); ?>
                <?php echo $form->error($model,'iban'); ?>
                <?php echo $form->error($model,'card_number'); ?>
                <?php echo $form->error($model,'bank_name'); ?>
                <?php echo $form->error($model,'holder_name'); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'account_number'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->textField($model,'account_number',array('class'=>'form-control direct-ltr','placeholder'=>'Account Number')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'iban'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->textField($model,'iban',array('class'=>'form-control direct-ltr','placeholder'=>'IBAN')); ?>
            </div>
        </div>


        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'card_number'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->textField($model,'card_number',array('class'=>'form-control direct-ltr','placeholder'=>'Card Number')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'bank_name'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->textField($model,'bank_name',array('class'=>'form-control','placeholder'=>'Bank Name')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'holder_name'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->textField($model,'holder_name',array('class'=>'form-control','placeholder'=>'Holder Name')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-left">
                <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
            </div>
        </div>
    <?php $this->endWidget(); ?>
</div>
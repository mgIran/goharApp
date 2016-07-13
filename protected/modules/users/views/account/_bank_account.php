<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo CHtml::label('شماره حساب',''); ?>
    </div>
    <div class="col-md-8 pull-right">
        <? echo CHtml::textField('account_number',$model->account_number,array(
            'class'=>'form-control direct-ltr',
            'readonly'=>'readonly',
            'style' => 'cursor:text;',
        ));?>
    </div>
    <div class="col-md-4 pull-right">
        <?php echo CHtml::label('شبا',''); ?>
    </div>
    <div class="col-md-8 pull-right">
        <? echo CHtml::textField('iban',$model->iban,array(
            'class'=>'form-control direct-ltr',
            'readonly'=>'readonly',
            'style' => 'cursor:text;',
        ));?>
    </div>
    <div class="col-md-4 pull-right">
        <?php echo CHtml::label('شماره کارت',''); ?>
    </div>
    <div class="col-md-8 pull-right">
        <? echo CHtml::textField('card_number',$model->card_number,array(
            'class'=>'form-control direct-ltr',
            'readonly'=>'readonly',
            'style' => 'cursor:text;',
        ));?>
    </div>
    <div class="col-md-4 pull-right">
        <?php echo CHtml::label('نام بانک',''); ?>
    </div>
    <div class="col-md-8 pull-right">
        <? echo CHtml::textField('bank_name',$model->bank_name,array(
            'class'=>'form-control direct-ltr',
            'readonly'=>'readonly',
            'style' => 'cursor:text;',
        ));?>
    </div>
    <div class="col-md-4 pull-right">
        <?php echo CHtml::label('نام صاحب حساب',''); ?>
    </div>
    <div class="col-md-8 pull-right">
        <? echo CHtml::textField('holder_name',$model->holder_name,array(
            'class'=>'form-control direct-ltr',
            'readonly'=>'readonly',
            'style' => 'cursor:text;',
        ));?>
    </div>
</div>
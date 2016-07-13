<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo CHtml::activeLabelEx($model,'credit_charge'); ?>
    </div>
    <div class="col-md-4 pull-right">
        <?php echo CHtml::activeTextField($model,'credit_charge',array('class'=>'form-control direct-ltr','placeholder'=>'Credit Charge','style'=>'background:#ffffff;cursor:text;','readonly'=>'readonly')); ?>
    </div>
    <div class="col-md-4" style="padding-left: 0">
        <?php
        $this->widget('ext.iWebAjaxForm.iWebAjaxForm',array(
            'id' => 'credit-charge-form',
            'options' => array(
                'theme' => '<div class="ajax-form-overlay"><div class="ajax-form-area"><div class="ajax-form-container"></div><span class="ajax-form-cancel">انصراف</span></div></div>',
                'url' => Yii::app()->createAbsoluteUrl('users/manage/updateValue/?id='.$_GET['id'].'&type=credit'),
                'afterCloseForm' => 'js:function(){
                    overlay = $(this)[0].overlaySelector;
                    $("#Users_credit_charge").val($(overlay).find("input:first").val());
                }',
            ),
            'label' => 'افزایش یا کاهش اعتبار',
            'htmlOptions' => array(
                'class' => 'form-control btn btn-default submit'
            ),
        ));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo CHtml::activeLabelEx($model,'sms_charge'); ?>
    </div>
    <div class="col-md-4 pull-right">
        <?php echo CHtml::activeTextField($model,'sms_charge',array('class'=>'form-control direct-ltr','placeholder'=>'SMS Charge','style'=>'background:#ffffff;cursor:text;','readonly'=>'readonly')); ?>
    </div>
    <div class="col-md-4" style="padding-left: 0">
        <?php
        $this->widget('ext.iWebAjaxForm.iWebAjaxForm',array(
            'id' => 'sms-charge-form',
            'options' => array(
                'theme' => '<div class="ajax-form-overlay"><div class="ajax-form-area"><div class="ajax-form-container"></div><span class="ajax-form-cancel">انصراف</span></div></div>',
                'url' => Yii::app()->createAbsoluteUrl('users/manage/updateValue/'.$_GET['id']),
                'afterCloseForm' => 'js:function(){
                    overlay = $(this)[0].overlaySelector;
                    $("#Users_sms_charge").val($(overlay).find("input:first").val());
                }'
            ),
            'htmlOptions' => array(
                'class' => 'form-control btn btn-default submit'
            ),
        ));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo CHtml::activeLabelEx($model,'email_charge'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php echo CHtml::activeTextField($model,'email_charge',array('class'=>'form-control direct-ltr','placeholder'=>'Email Charge','style'=>'background:#ffffff;cursor:text;','readonly'=>'readonly')); ?>
    </div>
</div>
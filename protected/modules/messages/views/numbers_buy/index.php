<div class="form col-md-8 form-center" style="height: 194px;overflow: visible">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'messages-texts-numbers-check-form',
        'enableAjaxValidation'=>true,
        'htmlOptions' => array(
            'class' => 'col-md-6 col-md-offset-4',
        ),
        'clientOptions' => array(
            'validateOnSubmit' => TRUE,
            'afterValidate' => 'js:function(form, data, hasError){
                if(data.MessagesTextsNumbersCheck_show_message[0].indexOf("success") !== -1)
                {
                    var $link = createAbsoluteUrl("messages/numbers_buy/buy?prefix=" + $("#MessagesTextsNumbersCheck_prefix_id").val() + "&number=" + $("#MessagesTextsNumbersCheck_number").val());
                    $("#buy-number").attr("href",$link);
                    $("#buy-number").show();
                }
                else
                    $("#buy-number").hide();
                return false;
            }'
        )
    )); ?>


    <div class="row">
        <h6 class="pull-left">بررسی خط</h6>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'prefix_id'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                'model' => $model,
                'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                'label'=> $prefixes[key($prefixes)],
                'name'=>'prefix_id',
                'list'=> $prefixes,
                'id' => 'prefix_id',
                'value' => key($prefixes),
            )); ?>
            <?php echo $form->error($model,'prefix_id'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'number'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'number',array('class'=>'form-control direct-ltr','placeholder'=>'Number...')); ?>
            <?php echo $form->error($model,'number'); ?>
        </div>
    </div>
    <?php echo $form->hiddenField($model,'show_message'); ?>
    <div class="row">
        <div class="col-md-8 pull-left">
            <?php echo CHtml::submitButton('بررسی', array('class'=>'form-control btn btn-default submit pull-right','style'=>'display:block;width:49%;')); ?>
            <?php echo CHtml::link('خرید خط','#', array('class'=>'form-control btn btn-default submit pull-left','style'=>'display:none;width:49%;border:none;line-height:35px;','id'=>'buy-number')); ?>
        </div>
    </div>
    <div class="row errors">
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-8 pull-right">
            <?php echo $form->error($model,'show_message'); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>

<? $this->renderPartial('_specials',array(
    'specialsModel' => $specialsModel
))?>
<? if (($flashMessage = Yii::app()->user->getFlash('success')) !== null): ?>
    <div class="alert alert-success">
        <i class="fa fa-check-square-o fa-lg"></i>
        <?= $flashMessage; ?>
        <div class="fa fa-times alert-close"></div>
    </div>
<? endif; ?><? if (($flashMessage = Yii::app()->user->getFlash('failed')) !== null): ?>
    <div class="alert alert-failed">
        <i class="fa fa-frown-o fa-lg"></i>
        <?= $flashMessage; ?>    </div>
<? endif; ?>

<div class="form">

    <?php
    $address = (Yii::app()->user->type == 'user')?'reply':'adminReply';
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'tickets-content-form',
        'htmlOptions' => array( 'enctype' => 'multipart/form-data','class' => 'col-md-11 pull-right'),
        'action'=> Yii::app()->createUrl("//tickets/content/$address?id=").$currentTicket,
        'enableAjaxValidation'=>true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false,
            'afterValidate' => "js:ajaxSubmitTicket",
        )
    )); ?>


    <div class="row errors">
        <div class="col-md-2 pull-right"></div>
        <div class="col-md-10 pull-right">
            <?php echo $form->error($model,'text'); ?>
            <?php echo $form->error($model,'file'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 pull-right">
            <?php echo $form->labelEx($model,'text'); ?>
        </div>
        <div class="col-md-10 pull-right">
            <?php echo $form->textArea($model,'text',array('class'=>'form-control','placeholder'=>'Text...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 pull-right">
            <?php echo $form->labelEx($model,'file'); ?>
        </div>
        <div class="col-md-10 pull-right">
            <span class="field-comment" style="margin-right: 5px">
                (فایل های مجاز: jpg, gif, jpeg, png, doc, docx, bmp, zip, rar, pdf)
        </span>
            <?php echo $form->fileField($model, 'file', array('class'=>'btn','accept'=>'.jpg,.gif,.jpeg,.png,.doc,.docx,.bmp,.zip,.rar,.pdf')); ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-2 pull-left">
            <?php echo CHtml::submitButton('ارسال' , array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>

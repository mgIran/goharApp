<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tickets-form',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data' ,
        'class' => 'col-md-6 col-md-offset-4'
    ),
	'enableAjaxValidation'=>true,
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    )
));
?>
    <div class="row">
        <h6 class="pull-left"><?=static::$actionsArray[$this->action->id]['title']?></h6>
    </div>

    <div class="row errors">
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-8 pull-right">
            <?php echo $form->errorSummary($model); ?>
            <?php echo $form->error($model,'title'); ?>
            <?php echo $form->error($model,'user_id'); ?>
            <?php echo $form->error($model,'cat_id'); ?>
            <?php echo $form->error($model,'priority'); ?>
            <?php echo $form->error($model,'text'); ?>
            <?php echo $form->error($model,'file'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'title'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'title',array('class'=>'form-control','placeholder'=>'Title...')); ?>
        </div>
    </div>

    <?if($model->scenario == 'admin_insert'):?>
        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'user_id'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php $this->widget('application.extensions.iWebDropDown.iWebDropDown', array(
                    'model'=>$model,
                    'icon' => '<div class="fa fa-angle-down"></div>',
                    'name'=>'cat_id',
                    'id'=>'dd_cat_id',
                    'label'=>'انتخاب کنید',
                    'list'=> Users::listForDropDown(),
                    'headCssClass'=>'dropdown-head',
                    'optionCssClass'=>'dropdown-option'
                )); ?>
            </div>
        </div>
    <?endif;?>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'cat_id'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php $this->widget('application.extensions.iWebDropDown.iWebDropDown', array(
                'model'=>$model,
                'icon' => '<div class="fa fa-angle-down"></div>',
                'name'=>'cat_id',
                'id'=>'dd_cat_id',
                'label'=>'انتخاب کنید',
                'list'=> Tickets::categories(),
                'headCssClass'=>'dropdown-head',
                'optionCssClass'=>'dropdown-option'
            )); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'priority'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php $this->widget('application.extensions.iWebDropDown.iWebDropDown', array(
                'model'=>$model,
                'icon' => '<div class="fa fa-angle-down"></div>',
                'name'=>'priority',
                'id'=>'dd_priority',
                'label'=>'انتخاب کنید',
                'list'=> Tickets::priorityList()
            )); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'text'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textArea($model,'text',array('class'=>'form-control','placeholder'=>'Text...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'file'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <span class="field-comment" style="margin-right: 5px">
                (فایل های مجاز: jpg, gif, jpeg, png, doc, docx, bmp, zip, rar, pdf)
        </span>
            <?php echo $form->fileField($model, 'file', array('class'=>'input-button btn-gray width-100','accept'=>'.jpg,.gif,.jpeg,.png,.doc,.docx,.bmp,.zip,.rar,.pdf')); ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-4 pull-left">
		    <?php echo CHtml::submitButton('ارسال' , array('class'=>'form-control btn btn-default submit')); ?>
        </div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
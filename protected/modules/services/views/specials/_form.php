<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'special-services-form',
        'enableAjaxValidation'=>true,
        //'enableClientValidation'=>true,
        'htmlOptions' => array(
            'class' => 'col-md-6 col-md-offset-4'
        ),
        'clientOptions' => array(
            'validateOnSubmit' => TRUE,
        )
    )); ?>

        <div class="row">
            <h6 class="pull-left"><?=static::$actionsArray[$this->action->id]['title']?></h6>
        </div>

        <div class="row errors">
            <div class="col-md-4 pull-right"></div>
            <div class="col-md-8 pull-right">
                <?php echo $form->errorSummary($model); ?>
                <?php echo $form->error($model,'title'); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'number_id'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                    'model' => $model,
                    'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                    'label'=> ((!is_null($model->number_id))?$model->number->number:'انتخاب کنید...'),
                    'name'=>'number_id',
                    'id' => 'SpecialsServices_number_id_dropdown',
                    'list'=> $numbers,
                )); ?>
                <? echo $form->error($model,'number_id')?>
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

        <div class="row">
            <div class="col-md-4 pull-right">
                <? echo $form->labelEx($model,'auto_answer')?>
            </div>
            <div class="col-md-8 pull-right">
                <? echo $form->textArea($model,'auto_answer',array(
                    'class' => 'form-control',
                    'rows' => 3,
                    'maxlength' => 640,
                ));?>
                <? echo $form->error($model,'auto_answer')?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'status'); ?>
            </div>
            <div class="col-md-4 pull-right">
                <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                    'model' => $model,
                    'label'=>($model->status == 0)?'غیر فعال':'فعال',
                    'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                    'name'=>'status',
                    'id' => 'Sp_status',
                    'list'=> array('غیر فعال','فعال') ,
                    'value' => SpecialServices::STATUS_ENABLE,
                )); ?>
                <?php echo $form->error($model,'status'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 pull-right"></div>
            <div class="col-md-8 pull-right"><hr/></div>
        </div>

        <?
        if($this->_type != SpecialServices::TYPE_OVERALL AND $this->_type != SpecialServices::TYPE_JOINING)
            $this->renderPartial("/specials/_options",array(
                'form' => $form,
                'model' => $model
            ));
        else
            $this->renderPartial("/specials/_overall",array(
                'form' => $form,
                'model' => $model,
                'overallModel' => $overallModel,
            ));

        ?>


        <div class="row">
            <div class="col-md-4 pull-left">
                <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
            </div>
        </div>

    <?php $this->endWidget(); ?>
</div>

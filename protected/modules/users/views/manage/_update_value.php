<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'ajax-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        )
    )); ?>
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
    <?if(isset($title)):?>
        <div class="row">
            <h6 class="pull-left"><?=$title?></h6>
        </div>
    <?endif;?>

    <?foreach($fields as $field):
        if(is_array($field))
            $field = (object)$field;
        $type = $field->type;
        ?>
        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,$field->name,array('class'=>'pull-right')); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?if($type == 'dropDownList'):?>
                    <?php echo $form->$type($model,$field->name,$field->list,array('class'=>'form-control'.((isset($field->ltr) AND $field->ltr)?' direct-ltr':''),'placeholder'=>(isset($field->english_title))?$field->english_title:'')); ?>
                <?else:?>
                    <?php echo $form->$type($model,$field->name,array('class'=>'form-control'.((isset($field->ltr) AND $field->ltr)?' direct-ltr':''),'placeholder'=>(isset($field->english_title))?$field->english_title:'')); ?>
                <?endif;?>
            </div>
            <?php echo $form->error($model,$field->name); ?>
        </div>
    <?endforeach;?>

    <div class="row" style="margin-top: 24px;padding: 0">
        <div class="col-md-4 pull-left">
            <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'messages-texts-users-numbers-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'htmlOptions' => array(
            'class' => 'col-md-6 col-md-offset-4'
        ),
    )); ?>

        <div class="row">
            <h6 class="pull-left"><?=static::$actionsArray[$this->action->id]['title']?></h6>
        </div>

        <div class="row errors">
            <div class="col-md-4 pull-right"></div>
            <div class="col-md-8 pull-right">
                <?php echo $form->errorSummary($model); ?>
                <?php echo $form->error($model,'user_id'); ?>
                <?php echo $form->error($model,'number'); ?>
                <?php echo $form->error($model,'status'); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'user_id'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php
                $options = array(
                    'model' => $model,
                    //'name' => 's',
                    'attribute'=>'user_id',
                    'source'=>$this->createUrl('users'),
                    'htmlOptions' => array(
                        'class' => 'form-control'
                    )
                );
                if(!is_null($model->user_id))
                    $model->user_id = $model->user->id .  ' - ' . $model->user->email;

                $this->widget('zii.widgets.jui.CJuiAutoComplete', $options);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'number'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->textField($model,'number',array('class'=>'form-control direct-ltr','placeholder'=>'Number...')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'status'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                    'model' => $model,
                    'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                    'label'=> MessagesTextsUsersNumbers::$statusList[$model->status],
                    'name'=>'status',
                    'list'=> MessagesTextsUsersNumbers::$statusList,
                    'id' => 'MessagesTextsUsersNumbers_status_dropdown',
                    'value' => $model->status
                )); ?>
            </div>
        </div>



        <div class="row">
            <div class="col-md-4 pull-left">
                <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
            </div>
        </div>

    <?php $this->endWidget(); ?>
</div>
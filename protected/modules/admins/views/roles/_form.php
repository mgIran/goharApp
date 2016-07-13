
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'admins-form',
        'enableAjaxValidation'=>(($model->scenario=='register')?false:true),
        'enableClientValidation' => true,
        'htmlOptions' => array(
            'class' => 'col-md-6 col-md-offset-4'
        ),
        'clientOptions' => array(
            'validateOnSubmit' => true,
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
            <?php echo $form->labelEx($model,'title'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'title',array('class'=>'form-control','placeholder'=>'Title...')); ?>
        </div>
    </div>

    <div style="clear:both"></div>
    <div class="row">
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-8 pull-right">
            <?
            if($model->name != 'admin')
                $this->widget('application.modules.admins.extensions.RolesJsTree.RolesJsTree', array(
                    'name' => 'AdminsRoles[permissions]',
                    'classes' => $this->getArrayOfControllers(),
                    'currentPermissions' => $model->permissions
                ));
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-left">
            <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>

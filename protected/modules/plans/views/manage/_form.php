<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/ckeditor/ckeditor.js');
$labels=$model->attributeLabels();
/* @var $this PlansManageController */
/* @var $model Plans */
/* @var $form CActiveForm */
?>

<div class="form col-md-12">
<div class="row" style="border-bottom: 1px solid #aaa;margin-bottom: 10px">
    <h2 class="pull-right"><?=static::$actionsArray[$this->action->id]['title']?></h2>
</div>
    <!-- Head buttons-->
<?php $form=$this->beginWidget('application.components.iWebActiveForm', array(
	'id'=>'plans-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
    'htmlOptions' => array(
        'class' => ''
    ),
)); ?>
    <div class="col-md-6 pull-right">
        <div>
            <div class="row">
                <h6 class="pull-right">
                    مقدمه
                </h6>
            </div>

            <div class="row errors">
                <div class="col-md-4 pull-right"></div>
                <div class="col-md-8 pull-right">
                    <?php echo $form->errorSummary($model); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 pull-right">
                    <?php echo $form->labelEx($model,'name'); ?>
                </div>
                <div class="col-md-8 pull-right">
                    <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
                    <?php echo $form->error($model,'name'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 pull-right">
                    <?php echo $form->labelEx($model,'factor_name'); ?>
                </div>
                <div class="col-md-8 pull-right">
                    <?php echo $form->textField($model,'factor_name',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
                    <?php echo $form->error($model,'factor_name'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 pull-right">
                    <?php echo $form->labelEx($model,'real_price'); ?>
                </div>
                <div class="col-md-2 pull-right">
                    <?php echo $form->textField($model,'real_price',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
                    <?php echo $form->error($model,'real_price'); ?>
                </div>
                <div class="col-md-4 pull-right">
                    <?php echo $form->labelEx($model,'approved_price'); ?>
                </div>
                <div class="col-md-2 pull-right">
                    <?php echo $form->textField($model,'approved_price',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
                    <?php echo $form->error($model,'approved_price'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 pull-right">
                    <?php echo $form->labelEx($model,'active'); ?>
                </div>
                <?if($model->id != 3):?>
                    <div class="col-md-4 pull-right">
                        <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                            'model' => $model,
                            'label'=>$model::$statusList[$model->active],
                            'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                            'name'=>'active',
                            'id' => 'Plans_active',
                            'list'=> $model::$statusList ,
                            'value' => $model->active
                        )); ?>
                        <?php echo $form->error($model,'active'); ?>
                    </div>
                <?endif;?>
            </div>

            <div class="row">
                <div class="col-md-4 pull-right">
                    <?php echo $form->labelEx($model,'disable_login'); ?>
                </div>
                <div class="col-md-4 pull-right">
                    <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                        'model' => $model,
                        'label'=>$model::$disableLoginList[$model->disable_login],
                        'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                        'name'=>'disable_login',
                        'id' => 'Plans_disable_login',
                        'list'=> $model::$disableLoginList ,
                        'value' => $model->disable_login
                    )); ?>
                    <?php echo $form->error($model,'disable_login'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 pull-right">
                    <?php echo $form->labelEx($model,'expire_time'); ?>
                </div>
                <div class="col-md-2 pull-right">
                    <?php echo $form->textField($model,'expire_time',array('class'=>'form-control pu')); ?>
                </div>
                <div class="col-md-1 pull-right">
                    <label>روز</label>
                </div>
                <div class="col-md-3 pull-right">
                    <?php echo $form->labelEx($model,'color'); ?>
                </div>
                <div class="col-md-2 pull-right">
                    <?php $this->widget('application.modules.plans.extensions.colorpicker.EColorPicker',
                        array(
                            'name'=>'Plans[color]',
                            'mode'=>'textfield',
                            'fade' => false,
                            'slide' => false,
                            'curtain' => true,
                            'value' => $model->color,
                            'htmlOptions' => array('class'=>'form-control')
                        )
                    );
                    ?>
                    <?php echo $form->error($model,'color'); ?>
                </div>
            </div>
        </div>
        <div>
            <hr/>
            <h6 class="pull-right">
                <?=$labels['pages']?>
            </h6>
            <div class="row">
                <?php
                echo $form->serializeFields($model,'pages',Plans::getPagesRangeTitle(),array(),array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'pages'); ?>
            </div>
            <hr/>
            <h6 class="pull-right">
                <?=$labels['ratio']?>
            </h6>
            <div class="row">
                <div class="col-md-12 pull-right">
                    <?php echo $form->labelEx($model,'ratio_send',array('style'=>'float:right')); ?>
                </div>
            </div>
            <div class="row">
                <?php echo $form->serializeFields($model,'ratio',$model->serializedFields['ratio_send'],array(),array('class'=>'form-control')); ?>
            </div>

            <div class="row">
                <div class="col-md-12 pull-right">
                    <?php echo $form->labelEx($model,'ratio_receive',array('style'=>'float:right')); ?>
                </div>
            </div>
            <div class="row">
                <?php echo $form->serializeFields($model,'ratio',$model->serializedFields['ratio_receive'],array(),array('class'=>'form-control')); ?>
            </div>

            <div class="row">
                <div class="col-md-12 pull-right">
                    <?php echo $form->labelEx($model,'ratio_content',array('style'=>'float:right')); ?>
                </div>
            </div>
            <div class="row">
                <?php echo $form->serializeFields($model,'ratio',$model->serializedFields['ratio_content'],array(),array('class'=>'form-control')); ?>
            </div>

            <div class="row">
                <?php echo $form->error($model,'ratio'); ?>
            </div>

            <hr/>

            <div class="row">
                <h6 class="pull-right">
                    تخفیف پلنی
                </h6>
            </div>

            <div class="row">
                <div class="col-md-4 pull-right">
                    <?php echo $form->labelEx($model,'extension_discount',array('style'=>'float:right')); ?>
                </div>
                <div class="col-md-8 pull-right">
                    <?php echo $form->textField($model,'extension_discount',array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'extension_discount'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 pull-right">
                    <?php echo $form->labelEx($model,'extension_discount_sections',array('style'=>'float:right')); ?>
                </div>
            </div>
            <div class="row agency-profit">
                <?php
                $checkBoxTemplate = array(
                    'start' =>      '<div class="row">',
                    'end' =>        '</div>',
                    'labelStart' => '<div class="col-md-11 pull-right">',
                    'labelEnd' =>   '</div>',
                    'textStart'=>   '<div class="col-md-1 pull-right">',
                    'textEnd'=>     '</div>'
                );
                echo $form->serializeFields($model,'extension_discount_sections',$model->serializedFields['extension_discount_sections'],array(),array(),'checkBox',$checkBoxTemplate); ?>
                <?php echo $form->error($model,'extension_discount_sections'); ?>
            </div>

            <hr/>

            <div class="row">
                <h6 class="pull-right">
                    <?=$labels['agency']?>
                </h6>
            </div>
            <div class="row">
                <?php
                $template = array(
                    'start' =>      '<div class="row">',
                    'end' =>        '</div>',
                    'labelStart' => '<div class="col-md-5 pull-right">',
                    'labelEnd' =>   '</div>',
                    'textStart'=>   '<div class="col-md-7 pull-right">',
                    'textEnd'=>     '</div>'
                );
                echo $form->serializeFields($model,'agency',$model->serializedFields['agency'],array(),array('class'=>'form-control','style'=>'width:auto'),'checkBox,textField',$template); ?>
                <?php echo $form->error($model,'agency'); ?>
            </div>
            <hr/>

            <div class="row">
                <h6 class="pull-right">
                    <?=$labels['agency_profit_sections']?>
                </h6>
            </div>
            <div class="row agency-profit">
                <?php echo $form->serializeFields($model,'agency_profit_sections',$model->serializedFields['agency_profit_sections'],array(),array(),'checkBox',$checkBoxTemplate); ?>
                <?php echo $form->error($model,'agency_profit_sections'); ?>
            </div>

            <br/>
            <div class="row">
                <div class="col-md-4 pull-left">
                    <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5 pull-left">
        <div class="col-md-12 pull-left">
            <div class="row">
                <h6 class="pull-right">
                    دسترسی کاربران
                </h6>
            </div>
            <?
            $this->widget('application.modules.users.extensions.RolesJsTree.RolesJsTree', array(
                'name' => 'UsersRoles[permissions]',
                'classes' => $this->getArrayOfControllers(),
                'currentPermissions' => $rolesModel->permissions
            ));
            ?>
        </div>
        <div class="col-md-12 pull-left">
            <div class="row">
                <h6 class="pull-right">
                    <?=$labels['required_fields']?>
                </h6>
            </div>
            <?
            $this->renderPartial("_required_fields",array(
                'values' => (!is_null($model->required_fields)?json_decode($model->required_fields,TRUE):NULL)
            ));
            ?>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?
$cs = Yii::app()->clientScript;
$cs->registerCss('rowReset','
.row{margin:0}
.agency-profit .row{
    float:right;
    width:33%;
}
#modules_tree{
    overflow:hidden;
    max-height:1700px;
}
');

$cs->registerScript("scrollModulesTree","
    $('#modules_tree').niceScroll();
");
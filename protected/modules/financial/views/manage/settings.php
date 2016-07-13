<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/ckeditor/ckeditor.js');
/* @var $this PlansManageController */
/* @var $model Plans */
/* @var $form CActiveForm */
?>
<? $this->widget("ext.iWebFunctions.iWebFunctions");?>
<div class="form col-md-12">
<div class="row" style="border-bottom: 1px solid #aaa;margin-bottom: 10px">
    <h2 class="pull-right"><?=static::$actionsArray[$this->action->id]['title']?></h2>
</div>
    <!-- Head buttons-->
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'plans-settings-form',
    'htmlOptions' => array(
        'class' => ''
    ),
)); ?>
    <div class="col-md-8 pull-right">
        <div class="row errors">
            <div class="col-md-2 pull-right"></div>
            <div class="col-md-10 pull-right">
                <?php echo $form->errorSummary($pagesModel); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 pull-right">
                <?php echo CHtml::label($pagesModel->title,'Pages_text');?>
            </div>
            <div class="col-md-10 pull-right">
                <?php echo $form->textArea($pagesModel,'text',array('class'=>'ckeditor')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 pull-right">
                <?php echo CHtml::label($buyHelp->title,'Pages_buy_help');?>
            </div>
            <div class="col-md-10 pull-right">
                <?php echo CHtml::textArea("Pages[buy_help]",$buyHelp->text,array('class'=>'ckeditor')); ?>
            </div>
        </div>
    </div>

    <div class="col-md-4 class-pull-left">
        <div class="row">
            <div class="col-md-2 pull-right"></div>
            <div class="col-md-1 pull-right">
                <div class="fa <?=(1)?'fa-check-square-o':'fa-square-o'?> pull-right"></div>
            </div>
            <div class="col-md-9 pull-right">
                <?php echo CHtml::label('
                درگاه بانک پارسیان
                ','Pages_parsian_gateway_status',array('class'=>'pull-right'));?>
                <br/>
                <?php
                $settings['parsian_gateway_status']['value'] = json_decode($settings['parsian_gateway_status']['value'],TRUE);
                $checked = ($settings['parsian_gateway_status']->value=='1')?TRUE:FALSE; ?>
                <div class="pull-left">
                    <?php echo CHtml::label('فعال','Pages_parsian_gateway_status_enable',array('class'=>'pull-right')); ?>
                    <?php echo CHtml::radioButton('Pages[parsian_gateway_status]',$checked,array('value'=>'1','id'=>'Pages_parsian_gateway_status_enable','class'=>'pull-right'));?>
                    <?php echo CHtml::label('غیر فعال','Pages_parsian_gateway_status_disable',array('class'=>'pull-right','style'=>'margin-right:15px')); ?>
                    <?php echo CHtml::radioButton('Pages[parsian_gateway_status]',!$checked,array('value'=>'0','id'=>'Pages_parsian_gateway_status_disable','class'=>'pull-right')); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 pull-right"></div>
            <div class="col-md-1 pull-right">
                <div class="fa <?=(0)?'fa-check-square-o':'fa-square-o'?> pull-right"></div>
            </div>
            <div class="col-md-9 pull-right">
                <?php echo CHtml::label('
                درگاه بانک قوامین
                ','Pages_ghavamin_gateway_status',array('class'=>'pull-right'));?>
                <br/>
                <?php
                $settings['ghavamin_gateway_status']['value'] = json_decode($settings['ghavamin_gateway_status']['value'],TRUE);
                $checked = ($settings['ghavamin_gateway_status']->value=='1')?TRUE:FALSE; ?>
                <div class="pull-left">
                    <?php echo CHtml::label('فعال','Pages_ghavamin_gateway_status_enable',array('class'=>'pull-right')); ?>
                    <?php echo CHtml::radioButton('Pages[ghavamin_gateway_status]',$checked,array('value'=>'1','id'=>'Pages_ghavamin_gateway_status_enable','class'=>'pull-right'));?>
                    <?php echo CHtml::label('غیر فعال','Pages_ghavamin_gateway_status_disable',array('class'=>'pull-right','style'=>'margin-right:15px')); ?>
                    <?php echo CHtml::radioButton('Pages[ghavamin_gateway_status]',!$checked,array('value'=>'0','id'=>'Pages_ghavamin_gateway_status_disable','class'=>'pull-right')); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 pull-right"></div>
            <div class="col-md-1 pull-right">
                <div class="fa <?=(0)?'fa-check-square-o':'fa-square-o'?> pull-right"></div>
            </div>
            <div class="col-md-9 pull-right">
                <?php echo CHtml::label('
                سود سایت:
                ','Pages_mellat_gateway_status',array('class'=>'pull-right'));?>
                <br/>
                <?php echo CHtml::label('
                درگاه بانک ملت
                ','Pages_mellat_gateway_status',array('class'=>'pull-right'));?>
                <br/>
                <?php
                $settings['mellat_gateway_status']['value'] = json_decode($settings['mellat_gateway_status']['value'],TRUE);
                $checked = ($settings['mellat_gateway_status']->value=='1')?TRUE:FALSE; ?>
                <div class="pull-left">
                    <?php echo CHtml::label('فعال','Pages_mellat_gateway_status_enable',array('class'=>'pull-right')); ?>
                    <?php echo CHtml::radioButton('Pages[mellat_gateway_status]',$checked,array('value'=>'1','id'=>'Pages_mellat_gateway_status_enable','class'=>'pull-right'));?>
                    <?php echo CHtml::label('غیر فعال','Pages_mellat_gateway_status_disable',array('class'=>'pull-right','style'=>'margin-right:15px')); ?>
                    <?php echo CHtml::radioButton('Pages[mellat_gateway_status]',!$checked,array('value'=>'0','id'=>'Pages_mellat_gateway_status_disable','class'=>'pull-right')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <br/>

    <div class="col-md-2 pull-left">
        <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
    </div>

    <div class="clearfix"></div>
    <br/>

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
    max-height:500px;
}
');

$cs->registerScript("scrollModulesTree","
    $('#modules_tree').niceScroll();
");
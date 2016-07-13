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
    <div class="col-md-12 pull-right">
        <div class="row errors">
            <div class="col-md-2 pull-right"></div>
            <div class="col-md-10 pull-right">
                <?php echo $form->errorSummary($pagesModel); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 pull-right">
                <?php echo CHtml::label('سرمایه محصول','Pages_plan_investment');?>
            </div>
            <div class="col-md-1 pull-right"></div>
            <div class="col-md-1 pull-right">
                <?php
                $checked = (strpos($settings['plan_investment']['value'],'%'))?TRUE:FALSE; ?>
                <?php echo CHtml::label('مبلغی','Pages_plan_investment_radio_price',array('class'=>'pull-right')); ?>
                <?php echo CHtml::radioButton('Pages[plan_investment_radio]',!$checked,array('value'=>'','id'=>'Pages_plan_investment_radio_price','class'=>'pull-right')); ?>
                <div class="clearfix"></div>
                <?php echo CHtml::label('درصدی','Pages_plan_investment_radio_percent',array('class'=>'pull-right')); ?>
                <?php echo CHtml::radioButton('Pages[plan_investment_radio]',$checked,array('value'=>'%','id'=>'Pages_plan_investment_radio_percent','class'=>'pull-right'));?>
            </div>
            <div class="col-md-1 pull-right">
                <?php echo CHtml::textField('Pages[plan_investment]',number_format(intval($settings['plan_investment']['value'])),array(
                    'class'=>'form-control direct-ltr',
                    'onKeyUp' => '$(this).val(iWebFunctions.splitNumber($(this).val()));',
                    'maxlength' => $checked?2:255,
                )); ?>
            </div>
            <div class="col-md-1 pull-right">
                <span class="unity" id="plan_investment_type"><?=($checked)?'درصد':'تومان'?></span>
                <?Yii::app()->clientScript->registerScript("plan_investment",'
                    $(document).on("click","#Pages_plan_investment_radio_price",function(){
                        $("#plan_investment_type").text("تومان");
                        $("#plan_investment_type").attr("maxlength",255);
                    });
                    $(document).on("click","#Pages_plan_investment_radio_percent",function(){
                        $("#plan_investment_type").text("درصد");
                        $("#plan_investment_type").attr("maxlength",2);
                    });

                ',CClientScript::POS_END);?>
            </div>

            <div class="col-md-1 pull-right">
                <?php echo CHtml::label('مالیات','Pages_tax');?>
            </div>
            <div class="col-md-1 pull-right">
                <?php echo CHtml::textField('Pages[tax]',$settings['tax']['value'],array(
                    'class'=>'form-control direct-ltr',
                    'maxlength' => '2'
                )); ?>
            </div>
            <div class="col-md-1 pull-right">
                <span class="unity">
                    درصد
                </span>
            </div>
        </div>

        <br/>
        <div class="row">
            <div class="col-md-2 pull-right">
                <?php echo CHtml::label('حداکثر تخفیف پلنی','Pages_plan_max_discount');?>
            </div>
            <div class="col-md-1 pull-right"></div>
            <div class="col-md-1 pull-right">
                <?php echo CHtml::textField('Pages[plan_max_discount]',$settings['plan_max_discount']['value'],array(
                    'class'=>'form-control direct-ltr',
                    'maxlength' => '2'
                )); ?>
            </div>
            <div class="col-md-1 pull-right">
                <span class="unity">
                    درصد
                </span>
            </div>
        </div>

        <br/>
        <br/>

        <div class="row">
            <div class="col-md-2 pull-right"></div>
            <div class="col-md-4 pull-right">
                <?php echo CHtml::label('
                کاربران سایت امکان انتخاب پلن جدید را دارند؟
                ','Pages_plan_investment',array('class'=>'pull-right'));?>

                <?php
                $checked = ($settings['plan_select']['value']=='1')?TRUE:FALSE; ?>
                <?php echo CHtml::label('بله','Pages_plan_select_yes',array('class'=>'pull-right')); ?>
                <?php echo CHtml::radioButton('Pages[plan_select]',$checked,array('value'=>'1','id'=>'Pages_plan_select_yes','class'=>'pull-right'));?>
                <?php echo CHtml::label('خیر','Pages_plan_select_no',array('class'=>'pull-right','style'=>'margin-right:15px')); ?>
                <?php echo CHtml::radioButton('Pages[plan_select]',!$checked,array('value'=>'0','id'=>'Pages_plan_select_no','class'=>'pull-right')); ?>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-2 pull-right"></div>
            <div class="col-md-10 pull-right">
                <?php echo CHtml::label('
                کاربران سایت امکان  لوگین دارند؟
                ','Pages_disable_login',array('class'=>'pull-right'));?>
                <?php
                $settings['disable_login']['value'] = json_decode($settings['disable_login']['value'],TRUE);
                $checked = ($settings['disable_login']['value']['status']=='1')?TRUE:FALSE; ?>
                <?php echo CHtml::label('بله','Pages_disable_login_yes',array('class'=>'pull-right')); ?>
                <?php echo CHtml::radioButton('Pages[disable_login][status]',!$checked,array('value'=>'0','id'=>'Pages_disable_login_yes','class'=>'pull-right'));?>
                <?php echo CHtml::label('خیر','Pages_disable_login_no',array('class'=>'pull-right','style'=>'margin-right:15px')); ?>
                <?php echo CHtml::radioButton('Pages[disable_login][status]',$checked,array('value'=>'1','id'=>'Pages_disable_login_no','class'=>'pull-right')); ?>
                <?Yii::app()->clientScript->registerScript("disable_login",'
                    $(document).on("click","#Pages_disable_login_yes",function(){
                        $("#disable_login_message").hide();
                    });
                    $(document).on("click","#Pages_disable_login_no",function(){
                        $("#disable_login_message").show();
                    });
                ',CClientScript::POS_END);?>
            </div>
        </div>
        <br/>
        <div class="row" id="disable_login_message"<?=(!$checked)?' style="display:none"':''?>>
            <div class="col-md-2 pull-right"></div>
            <div class="col-md-1 pull-right">
                <?php echo CHtml::label('
                پیغام
                    ','Pages_disable_login',array('class'=>'pull-right'));?>
            </div>
            <div class="col-md-9 pull-right">
                <?php echo CHtml::textField('Pages[disable_login][message]',$settings['disable_login']['value']['message'],array(
                    'class'=>'form-control',
                    'maxlength' => 450,
                )); ?>
            </div>
        </div>

        <br/>
        <div class="row">
            <div class="col-md-2 pull-right">
                <?php echo CHtml::label('درباره محصول','Pages_text');?>
            </div>
            <div class="col-md-10 pull-right">
                <?php echo $form->textArea($pagesModel,'text',array('class'=>'ckeditor')); ?>
                <?php echo $form->error($pagesModel,'text'); ?>
            </div>
        </div>

        <br/>
        <div class="row">
            <div class="col-md-4 pull-left">
                <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
            </div>
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
    max-height:500px;
}
');

$cs->registerScript("scrollModulesTree","
    $('#modules_tree').niceScroll();
");
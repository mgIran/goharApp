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
            <div class="col-md-6 pull-right">
                <div class="row">&nbsp;</div>
                <div class="row">&nbsp;</div>
                <div class="col-md-4 pull-right">
                    <?php echo CHtml::label('سرمایه محصول','Pages_sms_investment');?>
                </div>
                <div class="col-md-1 pull-right"></div>
                <div class="col-md-2 pull-right">
                    <?php
                    $checked = (strpos($settings['sms_investment']['value'],'%'))?TRUE:FALSE; ?>
                    <?php echo CHtml::label('مبلغی','Pages_sms_investment_radio_price',array('class'=>'pull-right')); ?>
                    <?php echo CHtml::radioButton('Pages[sms_investment_radio]',!$checked,array('value'=>'','id'=>'Pages_sms_investment_radio_price','class'=>'pull-right')); ?>
                    <div class="clearfix"></div>
                    <?php echo CHtml::label('درصدی','Pages_sms_investment_radio_percent',array('class'=>'pull-right')); ?>
                    <?php echo CHtml::radioButton('Pages[sms_investment_radio]',$checked,array('value'=>'%','id'=>'Pages_sms_investment_radio_percent','class'=>'pull-right'));?>
                </div>
                <div class="col-md-3 pull-right">
                    <?php echo CHtml::textField('Pages[sms_investment]',number_format(intval($settings['sms_investment']['value'])),array(
                        'class'=>'form-control direct-ltr',
                        'onKeyUp' => '$(this).val(iWebFunctions.splitNumber($(this).val()));',
                        'maxlength' => $checked?2:255,
                    )); ?>
                </div>
                <div class="col-md-2 pull-right">
                    <span class="unity" id="sms_investment_type"><?=($checked)?'درصد':'تومان'?></span>
                    <?Yii::app()->clientScript->registerScript("sms_investment",'
                        $(document).on("click","#Pages_sms_investment_radio_price",function(){
                            $("#sms_investment_type").text("تومان");
                            $("#sms_investment_type").attr("maxlength",255);
                        });
                        $(document).on("click","#Pages_sms_investment_radio_percent",function(){
                            $("#sms_investment_type").text("درصد");
                            $("#sms_investment_type").attr("maxlength",2);
                        });

                    ',CClientScript::POS_END);?>
                </div>

            </div>
            <div class="col-md-6 pull-right">
                <div class="row">
                    <?php
                    $smsPricesRange = json_decode($settings['sms_prices_range']['value'],TRUE);
                    echo CHtml::label('
                    مدیریت بازه های قیمت گذاری شارژ پیامک
                    ','Pages_sms_prices_range_0',array('class'=>'pull-right'));?>
                </div>
                <br/>
                <div class="row">
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::label('1)','Pages_sms_prices_range_0');?>
                    </div>
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::textField('Pages[sms_prices_range][0]',$smsPricesRange[0],array('class'=>'form-control','id'=>'Pages_sms_prices_range_0'));?>
                    </div>
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::label('تا','Pages_sms_prices_range_1');?>
                    </div>
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::textField('Pages[sms_prices_range][1]',$smsPricesRange[1],array('class'=>'form-control','id'=>'Pages_sms_prices_range_1'));?>
                    </div>
                    <div class="col-md-2 pull-right">
                        <span class="unity">
                            صفحه
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::label('2)','Pages_sms_prices_range_2');?>
                    </div>
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::textField('Pages[sms_prices_range][2]',$smsPricesRange[2],array('class'=>'form-control','id'=>'Pages_sms_prices_range_2'));?>
                    </div>
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::label('تا','Pages_sms_prices_range_3');?>
                    </div>
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::textField('Pages[sms_prices_range][3]',$smsPricesRange[3],array('class'=>'form-control','id'=>'Pages_sms_prices_range_3'));?>
                    </div>
                    <div class="col-md-2 pull-right">
                        <span class="unity">
                            صفحه
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::label('3)','Pages_sms_prices_range_4');?>
                    </div>
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::textField('Pages[sms_prices_range][4]',$smsPricesRange[4],array('class'=>'form-control','id'=>'Pages_sms_prices_range_4'));?>
                    </div>
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::label('تا','Pages_sms_prices_range_5');?>
                    </div>
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::textField('Pages[sms_prices_range][5]',$smsPricesRange[5],array('class'=>'form-control','id'=>'Pages_sms_prices_range_5'));?>
                    </div>
                    <div class="col-md-2 pull-right">
                        <span class="unity">
                            صفحه
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::label('4)','Pages_sms_prices_range_6');?>
                    </div>
                    <div class="col-md-2 pull-right"></div>
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::label('بیشتر از','Pages_sms_prices_range_6');?>
                    </div>
                    <div class="col-md-2 pull-right">
                        <? echo CHtml::textField('Pages[sms_prices_range][6]',$smsPricesRange[6],array('class'=>'form-control','id'=>'Pages_sms_prices_range_6'));?>
                    </div>

                    <div class="col-md-2 pull-right">
                        <span class="unity">
                            صفحه
                        </span>
                    </div>
                </div>
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

        <?$smsSendRange = json_decode($settings['sms_send_range']['value'],TRUE);?>
        <div class="row" style="font-size: 13px">

            <div class="row">
                <div class="col-md-2 pull-right">
                    <?php echo CHtml::label('
                    محدوده مجاز زمان ارسال پیامک
                    ','Pages_sms_send_range_0');?>
                </div>
                <div class="col-md-2 pull-right">
                    <div class="col-md-4 pull-right" style="text-align: center;padding-top: 10px">
                        ساعت
                    </div>
                    <div class="col-md-1 pull-right"></div>
                    <div class="col-md-4 pull-right" style="text-align: center;padding-top: 10px">
                        دقیقه
                    </div>
                    <div class="col-md-3 pull-right"></div>
                </div>
                <span class="unity" style="visibility: hidden">
                    الی
                </span>
                <div class="col-md-2 pull-right">
                    <div class="col-md-4 pull-right" style="text-align: center;padding-top: 10px">
                        ساعت
                    </div>
                    <div class="col-md-1 pull-right"></div>
                    <div class="col-md-4 pull-right" style="text-align: center;padding-top: 10px">
                        دقیقه
                    </div>
                    <div class="col-md-3 pull-right"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 pull-right">
                    <? echo CHtml::label('ارسال با امکانات مخابرات : از ساعت ','Pages_sms_send_range_0');?>
                </div>
                <div class="col-md-2 pull-right">
                    <div class="col-md-4 pull-right">
                        <? echo CHtml::textField('Pages[sms_send_range][0]',$smsSendRange[0],array('class'=>'form-control','id'=>'Pages_sms_send_range_0'));?>
                    </div>
                    <div class="col-md-1 pull-right">
                        <? echo CHtml::label('-','Pages_sms_prices_range_1');?>
                    </div>
                    <div class="col-md-4 pull-right">
                        <? echo CHtml::textField('Pages[sms_send_range][1]',$smsSendRange[1],array('class'=>'form-control','id'=>'Pages_sms_send_range_1'));?>
                    </div>
                </div>
                <span class="unity">
                    الی
                </span>
                <div class="col-md-2 pull-right">
                    <div class="col-md-4 pull-right">
                        <? echo CHtml::textField('Pages[sms_send_range][2]',$smsSendRange[2],array('class'=>'form-control','id'=>'Pages_sms_send_range_2'));?>
                    </div>
                    <div class="col-md-1 pull-right">
                        <? echo CHtml::label('-','Pages_sms_prices_range_3');?>
                    </div>
                    <div class="col-md-4 pull-right">
                        <? echo CHtml::textField('Pages[sms_send_range][3]',$smsSendRange[3],array('class'=>'form-control','id'=>'Pages_sms_send_range_3'));?>
                    </div>
                </div>
            </div>
            <div class="row" style="font-size: 13px">
                <div class="col-md-2 pull-right">
                    <? echo CHtml::label('ارسال با دفترچه مخاطبین : از ساعت ','Pages_sms_send_range_4');?>
                </div>
                <div class="col-md-2 pull-right">
                    <div class="col-md-4 pull-right">
                        <? echo CHtml::textField('Pages[sms_send_range][4]',$smsSendRange[4],array('class'=>'form-control','id'=>'Pages_sms_send_range_4'));?>
                    </div>
                    <div class="col-md-1 pull-right">
                        <? echo CHtml::label('-','Pages_sms_prices_range_5');?>
                    </div>
                    <div class="col-md-4 pull-right">
                        <? echo CHtml::textField('Pages[sms_send_range][5]',$smsSendRange[5],array('class'=>'form-control','id'=>'Pages_sms_send_range_5'));?>
                    </div>
                </div>
                <span class="unity">
                    الی
                </span>
                <div class="col-md-2 pull-right">
                    <div class="col-md-4 pull-right">
                        <? echo CHtml::textField('Pages[sms_send_range][6]',$smsSendRange[6],array('class'=>'form-control','id'=>'Pages_sms_send_range_6'));?>
                    </div>
                    <div class="col-md-1 pull-right">
                        <? echo CHtml::label('-','Pages_sms_prices_range_7');?>
                    </div>
                    <div class="col-md-4 pull-right">
                        <? echo CHtml::textField('Pages[sms_send_range][7]',$smsSendRange[7],array('class'=>'form-control','id'=>'Pages_sms_send_range_7'));?>
                    </div>
                </div>
            </div>
            <div class="row" style="font-size: 13px">
                <div class="col-md-2 pull-right">
                    <? echo CHtml::label('سیستم های ارسالی','Pages_sending_system');?>
                </div>
                <div class="col-md-4 pull-right">
                    <? echo CHtml::textField('Pages[sms_sending_system]',$settings['sms_sending_system']['value'],array('class'=>'form-control','id'=>'Pages_sms_sending_system'));?>
                </div>
            </div>
            <div class="row" style="font-size: 13px">
                <div class="col-md-2 pull-right">
                    <? echo CHtml::label('کاربرد ارسال :','Pages_send_usage');?>
                </div>
            </div>
            <div class="row" style="font-size: 13px">
                <div class="col-md-2 pull-right">
                    <? echo CHtml::label('الف','Pages_send_usage_1');?>
                </div>
                <div class="col-md-4 pull-right">
                    <? echo CHtml::textArea('Pages[sms_send_usage_1]',$settings['sms_send_usage_1']['value'],array('class'=>'form-control','id'=>'Pages_sms_send_usage_1'));?>
                </div>
            </div>
            <div class="row" style="font-size: 13px">
                <div class="col-md-2 pull-right">
                    <? echo CHtml::label('ب','Pages_send_usage_2');?>
                </div>
                <div class="col-md-4 pull-right">
                    <? echo CHtml::textArea('Pages[sms_send_usage_2]',$settings['sms_send_usage_2']['value'],array('class'=>'form-control','id'=>'Pages_sms_send_usage_2'));?>
                </div>
            </div>
        </div>

        <br/>
        <div class="row">
            <div class="col-md-2 pull-left">
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
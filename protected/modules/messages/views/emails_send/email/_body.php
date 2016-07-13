<?
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/ckeditor/ckeditor.js');

$cs->registerScript("selectize","
    var selectizeElement = null;
",CClientScript::POS_HEAD);
$form = $this->beginWidget('CActiveForm',array(
    'id' => 'messages-emails-send',
    'enableAjaxValidation' => true
));
?>
<br/>
<div class="form">
    <?foreach(Yii::app()->user->getFlashes() as $key => $message):?>
        <div class="alert alert-<?=$key?> alert-dismissible col-md-8 col-md-offset-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?=$message?>
        </div>
        <div class="clearfix"></div>
    <?endforeach;?>
    <div class="row">
        <div class="col-md-2 pull-right">
            <? echo $form->labelEx($model,'sender')?>
        </div>
        <div class="col-md-8 pull-right">
            <? echo $form->textField($model,'sender',array(
                'class' => 'form-control direct-ltr'
            ))?>
            <? echo $form->error($model,'sender')?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 pull-right">
            <? echo $form->labelEx($model,'to')?>
        </div>
        <div class="col-md-8 pull-right">
            <? $fromBank = "از طریق بانک ایمیل";
            $fromFile = "از طریق فایل";
            $fromContacts = "از طریق دفترچه مخاطبین";
            $selectTemplate = "انتخاب قالب";

            $this->renderPartial('email/_from_file',array(
                'title' => $fromFile
            ));
            if(count($emailsBankCategories))
                $this->renderPartial('email/_from_email_bank',array(
                    'title' => $fromBank,
                    'emailsBankCategories' => $emailsBankCategories
                ));


            $this->renderPartial('email/_from_contacts',array(
                'title' => $fromContacts,
                'contactsCategories' => $contactsCategories
            ));

            $this->renderPartial('email/_select_template',array(
                'title' => $selectTemplate,
                'templates' => $templates
            ));

            ?>

            <div class="col-md-4 to-other" style="padding-left: 0">
                <button type="button" class="btn btn-primary dropdown-toggle" style="width: 100%" data-toggle="dropdown" aria-expanded="false">
                    از طریق ... <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a data-toggle="modal" data-backdrop="static" data-target="#from-file" href="#" title="<?=$fromFile?>"><?=$fromFile?></a></li>
                   <? if(count($emailsBankCategories)):?>
                        <li><a data-toggle="modal" data-backdrop="static" data-target="#from-email-bank" href="#" title="<?=$fromBank?>"><?=$fromBank?></a></li>
                    <?endif;?>
                    <? if(count($contactsCategories)):?>
                        <li><a data-toggle="modal" data-backdrop="static" data-target="#from-contacts" href="#" title="<?=$fromContacts?>"><?=$fromContacts?></a></li>
                    <?endif;?>
                </ul>
            </div>
            <div class="col-md-8 padding-reset pull-right">
                <?
                $this->widget('ext.yii-selectize.YiiSelectize', array(
                    'model' => $model,
                    'attribute' => 'to',
                    'useWithBootstrap' => true,
                    'multiple' => true,

                    'options' => array(
                        'plugins' => array('remove_button'),
                    ),
                    'htmlOptions' => array(
                        'class' => 'direct-ltr form-control'
                    ),
                    'placeholder' => 'ایمیل جدید وارد کنید...',

                    'callbacks' => array(
                        'onInitialize' => 'function(data) {
                            selectizeElement = this;
                        }',
                        'onOptionAdd' => 'function(value,item) {
                            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                            if(!re.test(value))
                                this.removeOption(value);
                        }'

                    ),
                ));
                ?>
            </div>
            <? //echo $form->hiddenField($model,'to')?>
            <? echo $form->error($model,'to')?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 pull-right">
            <? echo $form->labelEx($model,'title')?>
        </div>
        <div class="col-md-8 pull-right">
            <? echo $form->textField($model,'title',array(
                'class' => 'form-control'
            ))?>
            <? echo $form->error($model,'title')?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-2 pull-right"></div>
        <div class="col-md-2 pull-right">
            <a class="btn btn-primary" data-toggle="modal" data-backdrop="static" data-target="#select-template" href="#" title="<?=$selectTemplate?>"><?=$selectTemplate?></a>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-2 pull-right">
            <? echo $form->labelEx($model,'body')?>
        </div>
        <div class="col-md-8 pull-right">
            <? echo $form->textArea($model,'body',array('class'=>'ckeditor'))?>
            <? echo $form->error($model,'body')?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 pull-right">
            <?php echo $form->labelEx($model,'send_time'); ?>
        </div>
        <div class="col-md-2 pull-right">
            <?php //echo $form->textField($overallModel,'start_time',array('class'=>'form-control','placeholder'=>'Start Time...'));
            //echo CHtml::textField('start_time',);
            $this->widget('ext.JalaliDatePicker.JalaliDatePicker',array('textField'=>'send_time',
                'options'=>array(
                    'changeMonth'=>'true',
                    'changeYear'=>'true',
                    'showButtonPanel'=>'true',
                    'changeDate' => 'true',
                ),
                'model' => $model,
            ));

            $this->widget('ext.timepicker.timepicker', array(
                'model'=>$model,
                'name'=>'send_time',
                'skin' => 'new',
                'options' => array(
                    'htmlOptions' => array('placeholder'=>'Send Time...')
                )
            ));

            ?>

            <? echo $form->error($model,'send_time')?>
        </div>
        <div class="col-md-1 pull-right">
            <?php echo $form->labelEx($model,'end_time'); ?>
        </div>
        <div class="col-md-4 pull-right">
            <?php //echo $form->textField($overallModel,'start_time',array('class'=>'form-control','placeholder'=>'Start Time...'));
            //echo CHtml::textField('start_time',);
            $this->widget('ext.JalaliDatePicker.JalaliDatePicker',array('textField'=>'end_time',
                'options'=>array(
                    'changeMonth'=>'true',
                    'changeYear'=>'true',
                    'showButtonPanel'=>'true',
                    'changeDate' => 'true',
                ),
                'model' => $model
            ));

            $this->widget('ext.timepicker.timepicker', array(
                'model'=>$model,
                'name'=>'end_time',
                'skin' => 'new',
                'options' => array(
                    'htmlOptions' => array('placeholder'=>'End Time...')
                )
            ));

            ?>

            <? echo $form->error($model,'end_time')?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1 col-md-offset-2" style="padding: 0">
            <?php echo CHtml::submitButton('ارسال', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>

</div>
<?$this->endWidget();?>
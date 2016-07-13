<?
$cs = Yii::app()->clientScript;
$cs->registerScript("selectize", "
        var selectizeElement = null;
    ", CClientScript::POS_HEAD);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'messages-texts-send',
    'enableAjaxValidation' => true
));

?>
    <br/>
    <div class="form">
        <input type="hidden" name="MessagesTextsSend_webserviceBanks" id="MessagesTextsSend_webserviceBanks">

        <input type="hidden" name="MessagesTextsSend_packages" id="MessagesTextsSend_packages">

        <? foreach (Yii::app()->user->getFlashes() as $key => $message): ?>
            <div class="alert alert-<?= $key ?> alert-dismissible col-md-8 col-md-offset-2" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <?= $message ?>
            </div>
            <div class="clearfix"></div>
        <? endforeach; ?>

        <div class="row">
            <div class="col-md-2 pull-right">
                <? echo $form->labelEx($model, 'sender_id') ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                    'model' => $model,
                    'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                    'label' => 'انتخاب کنید...',
                    'name' => 'sender_id',
                    'id' => 'MessagesMobiles_sender_id_dropdown',
                    'list' => $numbers,
                )); ?>
                <? echo $form->error($model, 'sender_id') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 pull-right">
                <? echo $form->labelEx($model, 'to') ?>
            </div>
            <div class="col-md-8 pull-right">
                <? $fromBank = "از طریق بانک شماره موبایل";
                $fromFile = "از طریق فایل";
                $fromContacts = "از طریق دفترچه مخاطبین";
                $fromWebservice = "از طریق امکانات مخابرات";

                $this->renderPartial('sms/_from_file', array(
                    'title' => $fromFile
                ));
                $this->renderPartial('sms/_from_webservice', array(
                    'title' => $fromWebservice,
                    'webserviceCategories' => $webserviceCategories
                ));
                $this->renderPartial('sms/_from_webservice_cities', array(
                    'title' => 'بر اساس تقسیمات کشوری',
                    'webserviceCategories' => $webserviceCategories
                ));
                $this->renderPartial('sms/_from_webservice_postalcodes', array(
                    'title' => 'بر اساس کد پستی',
                    'webserviceCategories' => $webserviceCategories
                ));

                if (count($mobilesBankCategories))
                    $this->renderPartial('sms/_from_mobile_bank', array(
                        'title' => $fromBank,
                        'mobilesBankCategories' => $mobilesBankCategories
                    ));

                $this->renderPartial('sms/_from_contacts', array(
                    'title' => $fromContacts,
                    'contactsCategories' => $contactsCategories
                ));
                ?>
                <div class="col-md-4 to-other" style="padding-left: 0;float:left">
                    <button type="button" class="btn btn-primary dropdown-toggle" style="width: 100%"
                            data-toggle="dropdown" aria-expanded="false">
                        از طریق ... <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" data-backdrop="static" data-target="#from-file" href="#"
                               title="<?= $fromFile ?>"><?= $fromFile ?></a></li>
                        <? if (count($mobilesBankCategories)): ?>
                            <li><a data-toggle="modal" data-backdrop="static" data-target="#from-mobile-bank" href="#"
                                   title="<?= $fromBank ?>"><?= $fromBank ?></a></li>
                        <? endif; ?>
                        <? if (count($contactsCategories)): ?>
                            <li><a data-toggle="modal" data-backdrop="static" data-target="#from-contacts" href="#"
                                   title="<?= $fromContacts ?>"><?= $fromContacts ?></a></li>
                        <? endif; ?>
                        <li><a data-toggle="modal" data-backdrop="static" data-target="#from-webservice" href="#"
                               title="<?= $fromWebservice ?>"><?= $fromWebservice ?></a></li>
                        <li><a data-toggle="modal" data-backdrop="static" data-target="#from-webservice-cities" href="#"
                               title="بر اساس تقسیمات کشوری">بر اساس تقسیمات کشوری</a></li>
                        <li><a data-toggle="modal" data-backdrop="static" data-target="#from-webservice-postalcodes"
                               href="#" title="بر اساس کد پستی">بر اساس کد پستی</a></li>
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
                        'placeholder' => 'شماره جدید وارد کنید...',

                        'callbacks' => array(
                            'onInitialize' => 'function(data) {
                            selectizeElement = this;
                        }',
                            'onOptionAdd' => 'function(value,item) {
                            var re = /^(0|\+98){0,1}9{1}\d{9}$/;
                            if(!re.test(value))
                                this.removeOption(value);
                        }'

                        ),
                    ));
                    ?>
                </div>
                <? //echo $form->hiddenField($model,'to')?>
                <? echo $form->error($model, 'to') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 pull-right">
            </div>
            <div class="col-md-8 pull-right">
                <ul class="packages-wrapper">

                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 pull-right">
                <? echo $form->labelEx($model, 'body') ?>
            </div>
            <div class="col-md-8 pull-right">
                <? echo $form->textArea($model, 'body', array(
                    'class' => 'form-control sms-calculate',
                    'rows' => 3,
                    'maxlength' => 640,
                )); ?>
                <div class="sms-statistics">
                    <span class="char">0</span>
                    <span>/</span>
                    <span class="max">160</span>
                    <span>&nbsp;&nbsp;</span>
                    <span>تعداد صفحات : </span>
                    <span class="page">0</span>
                </div>
                <? echo $form->error($model, 'body') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 pull-right">
                <? echo $form->labelEx($model, 'webservice_num') ?>
            </div>
            <div class="col-md-8 pull-right">
                <? echo $form->textField($model, 'webservice_num', array('class' => 'form-control')); ?>
                <div>
                    * کاربرد در استفاده از امکانات مخابرات می باشد.
                </div>
                <? echo $form->error($model, 'webservice_num') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 pull-right">
                <?php echo $form->labelEx($model, 'send_time'); ?>
            </div>
            <div class="col-md-2 pull-right">
                <?php //echo $form->textField($overallModel,'start_time',array('class'=>'form-control','placeholder'=>'Start Time...'));
                //echo CHtml::textField('start_time',);
                $this->widget('ext.JalaliDatePicker.JalaliDatePicker', array('textField' => 'send_time',
                    'options' => array(
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'showButtonPanel' => 'true',
                        'changeDate' => 'true',
                    ),
                    'model' => $model,
                ));

                $this->widget('ext.timepicker.timepicker', array(
                    'model' => $model,
                    'name' => 'send_time',
                    'skin' => 'new',
                    'options' => array(
                        'htmlOptions' => array('placeholder' => 'Send Time...')
                    )
                ));

                ?>

                <? echo $form->error($model, 'send_time') ?>
            </div>
            <div class="col-md-1 pull-right">
                <?php echo $form->labelEx($model, 'end_time'); ?>
            </div>
            <div class="col-md-4 pull-right">
                <?php //echo $form->textField($overallModel,'start_time',array('class'=>'form-control','placeholder'=>'Start Time...'));
                //echo CHtml::textField('start_time',);
                $this->widget('ext.JalaliDatePicker.JalaliDatePicker', array('textField' => 'end_time',
                    'options' => array(
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        'showButtonPanel' => 'true',
                        'changeDate' => 'true',
                    ),
                    'model' => $model
                ));

                $this->widget('ext.timepicker.timepicker', array(
                    'model' => $model,
                    'name' => 'end_time',
                    'skin' => 'new',
                    'options' => array(
                        'htmlOptions' => array('placeholder' => 'End Time...')
                    )
                ));

                ?>

                <? echo $form->error($model, 'end_time') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-1 col-md-offset-2" style="padding: 0">
                <?php echo CHtml::submitButton('ارسال', array('class' => 'form-control btn btn-default submit')); ?>
            </div>
        </div>
    </div>
<? $this->endWidget(); ?>
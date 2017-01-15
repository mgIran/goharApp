<?php
/* @var $this ManageController */
/* @var $form CActiveForm */
/* @var $showEventMessage SiteOptions */
/* @var $showEvent SiteOptions */
/* @var $showEventMoreThanDefaultPrice SiteOptions */
/* @var $showEventMoreThanDefault SiteOptions */
/* @var $eventMaxLongDays SiteOptions */
/* @var $showEventArrivedDeadline SiteOptions */
/* @var $submitGeneralEvents SiteOptions */
/* @var $program array */
/* @var $baseLine SiteOptions */
/* @var $appVersion SiteOptions */

$this->breadcrumbs=array(
	'تنظیمات',
);
?>
<h1>تنظیمات</h1>

<div class="form">

    <?php echo CHtml::beginForm(); ?>

    <div class="row">
        <?php echo CHtml::label('نمایش پیام به مخاطبین به صورت اتوماتیک',''); ?>
        <?php foreach(CJSON::decode($showEventMessage->value) as $key=>$value):?>
            <div>
                - مراسمات
                <?php echo CHtml::textField('showEventMessage['.$key.'][0]', $value[0], array('class'=>'event-message-field')) ?>
                الی
                <?php echo CHtml::textField('showEventMessage['.$key.'][1]', $value[1], array('class'=>'event-message-field')) ?>
                روزه:
                <?php echo CHtml::textField('showEventMessage['.$key.'][2]', $value[2], array('class'=>'event-message-field')) ?>
                ساعت قبل از شروع مراسم.
            </div>
        <?php endforeach;?>
    </div>

    <div class="row">
        <?php echo CHtml::label('هزینه ثبت و زمان پیش فرض نمایش مراسم',''); ?>
        <?php foreach(CJSON::decode($showEvent->value) as $key=>$value):?>
            <div>
                - نمایش
                <?php echo CHtml::textField('showEvent['.$key.'][0]', $value[0], array('class'=>'event-message-field')) ?>
                الی
                <?php echo CHtml::textField('showEvent['.$key.'][1]', $value[1], array('class'=>'event-message-field')) ?>
                شبانه روزه:
                <?php echo CHtml::textField('showEvent['.$key.'][2]', $value[2], array('class'=>'event-message-field')) ?>
                تومان.
            </div>
        <?php endforeach;?>
    </div>

    <div class="row">
        <?php echo CHtml::label('هزینه هر شبانه روز نمایش بیشتر از پیش فرض','showEventMoreThanDefaultPrice'); ?>
        <?php echo CHtml::textField('showEventMoreThanDefaultPrice', $showEventMoreThanDefaultPrice->value) ?>
    </div>

    <div class="row">
        <?php echo CHtml::label('حداکثر مدت زمان نمایش بیشتر از پیش فرض','showEventMoreThanDefault'); ?>
        <?php echo CHtml::textField('showEventMoreThanDefault', $showEventMoreThanDefault->value) ?>
    </div>

    <div class="row">
        <?php echo CHtml::label('حداکثر مدت زمان هر مراسم','eventMaxLongDays'); ?>
        <?php echo CHtml::textField('eventMaxLongDays', $eventMaxLongDays->value) ?>شبانه روز
    </div>

    <div class="row">
        <?php echo CHtml::label('مراسماتی که موعد نمایش آن میرسد نمایش داده شود؟','showEventArrivedDeadline'); ?>
        <div><?php echo CHtml::radioButtonList('showEventArrivedDeadline', $showEventArrivedDeadline->value,array('1'=>'بله','0'=>'خیر')); ?></div>
    </div>

    <div class="row">
        <?php echo CHtml::label('امکان ثبت مراسم به صورت سراسری برای کاربران وجود دارد؟','submitGeneralEvents'); ?>
        <div><?php echo CHtml::radioButtonList('submitGeneralEvents', $submitGeneralEvents->value,array('1'=>'بله','0'=>'خیر')); ?></div>
    </div>

    <hr>

    <div class="row">
        <?php echo CHtml::label('آپلود آخرین نسخه برنامه گهریاب',''); ?>
        <div>
            <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                'id' => 'program-uploader',
                'name'=>'gohar_yab_program',
                'maxFiles' => 1,
                'maxFileSize' => 100, //MB
                'url' => $this->createUrl('/setting/manage/upload'),
                'deleteUrl' => $this->createUrl('/setting/manage/deleteUpload'),
                'acceptedFiles' => '.apk',
                'serverFiles' => $program,
                'onSuccess' => '
                    var responseObj = JSON.parse(res);
                    if(responseObj.status){
                        {serverName} = responseObj.fileName;
                        $(".uploader-message").html("");
                    }
                    else{
                        $(".uploader-message").html(responseObj.message);
                        this.removeFile(file);
                    }
            ')); ?>
            <div class="uploader-message"></div>
        </div>
    </div>

    <div class="row">
        <?php echo CHtml::label('آخرین نسخه برنامه',''); ?>
        <a href="<?php echo Yii::app()->baseUrl . '/uploads/app/'.$program['name'];?>"><?php echo $program['name'];?></a>
    </div>

    <div class="row">
        <?php echo CHtml::label('شماره خط مجازی','baseLine'); ?>
        <?php echo CHtml::textField('baseLine', $baseLine->value); ?>
    </div>

    <div class="row">
        <?php echo CHtml::label('نسخه برنامه','appVersion'); ?>
        <?php echo CHtml::textField('appVersion', $appVersion->value); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('ذخیره', array('class'=>'btn btn-success','name'=>'submit')); ?>
    </div>

    <?php echo CHtml::endForm(); ?>

</div>

<?php Yii::app()->clientScript->registerCss('this-page-style', '
.event-message-field{
    display: inline-block;
    width: 80px;
}
.form .row{
    margin-bottom:40px;
}
');?>
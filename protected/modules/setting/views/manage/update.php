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
/* @var $eventTaxEnabled SiteOptions */
/* @var $signupStatus SiteOptions */
/* @var $adminGroupsPrice SiteOptions */
/* @var $generalFiltersPrice SiteOptions */
/* @var $favoriteFiltersPrice SiteOptions */
/* @var $weeklyUnityPoster array */
/* @var $adminGroupsTaxEnabled SiteOptions */
/* @var $generalFiltersTaxEnabled SiteOptions */
/* @var $favoriteFiltersTaxEnabled SiteOptions */

$this->breadcrumbs=array(
	'تنظیمات',
);
?>
<h1>تنظیمات</h1>

<div class="form">
    <?php echo CHtml::beginForm(); ?>

    <?php $this->renderPartial("//layouts/_flashMessage");?>

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#general">عمومی</a></li>
        <li><a data-toggle="tab" href="#version">نسخه برنامه</a></li>
        <li><a data-toggle="tab" href="#finance">مالی</a></li>
        <li><a data-toggle="tab" href="#weekly-unity">همصدایی هفتگی</a></li>
    </ul>

    <div class="tab-content">
        <div id="general" class="tab-pane fade in active">
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

            <div class="row">
                <?php echo CHtml::label('امکان عضویت از طریق برنامه موبایل وجود دارد؟','signupStatus'); ?>
                <div><?php echo CHtml::radioButtonList('signupStatus', $signupStatus->value,array('1'=>'بله','0'=>'خیر')); ?></div>
            </div>

            <div class="row">
                <?php echo CHtml::label('شماره خط مجازی','baseLine'); ?>
                <?php echo CHtml::textField('baseLine', $baseLine->value); ?>
            </div>
        </div>
        <div id="version" class="tab-pane fade">
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
                <?php if(isset($program['name'])):?>
                    <a href="<?php echo Yii::app()->baseUrl . '/uploads/app/'.$program['name'];?>"><?php echo $program['name'];?></a>
                <?php else:?>
                    <span class="errorMessage">فایل جدید برنامه آپلود نشده است!</span>
                <?php endif;?>
            </div>

            <div class="row">
                <?php echo CHtml::label('نسخه برنامه','appVersion'); ?>
                <?php echo CHtml::textField('appVersion', $appVersion->value); ?>
            </div>
        </div>
        <div id="finance" class="tab-pane fade">
            <div class="row">
                <?php echo CHtml::label('مالیات برای هر ثبت مراسم اعمال شود؟','tax'); ?>
                <div><?php echo CHtml::radioButtonList('eventTaxEnabled', $eventTaxEnabled->value, array(1=>'بله', 0=>'خیر'));?></div>
            </div>

            <div class="row">
                <?php echo CHtml::label('مالیات برای ایجاد گروه مدیریتی اضافی اعمال شود؟','adminGroupsTaxEnabled'); ?>
                <div><?php echo CHtml::radioButtonList('adminGroupsTaxEnabled', $adminGroupsTaxEnabled->value, array(1=>'بله', 0=>'خیر'));?></div>
            </div>

            <div class="row">
                <?php echo CHtml::label('مالیات برای ایجاد فیلتر عمومی اضافی اعمال شود؟','generalFiltersTaxEnabled'); ?>
                <div><?php echo CHtml::radioButtonList('generalFiltersTaxEnabled', $generalFiltersTaxEnabled->value, array(1=>'بله', 0=>'خیر'));?></div>
            </div>

            <div class="row">
                <?php echo CHtml::label('مالیات برای ایجاد فیلتر علاقه مندی اضافی اعمال شود؟','favoriteFiltersTaxEnabled'); ?>
                <div><?php echo CHtml::radioButtonList('favoriteFiltersTaxEnabled', $favoriteFiltersTaxEnabled->value, array(1=>'بله', 0=>'خیر'));?></div>
            </div>

            <hr>

            <div class="row">
                <?php echo CHtml::label('قیمت هر گروه مدیریتی اضافی','adminGroupsPrice'); ?>
                <?php echo CHtml::textField('adminGroupsPrice', $adminGroupsPrice->value); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('قیمت هر فیلتر عمومی اضافی','generalFiltersPrice'); ?>
                <?php echo CHtml::textField('generalFiltersPrice', $generalFiltersPrice->value); ?>
            </div>

            <div class="row">
                <?php echo CHtml::label('قیمت هر فیلتر علاقه مندی اضافی','favoriteFiltersPrice'); ?>
                <?php echo CHtml::textField('favoriteFiltersPrice', $favoriteFiltersPrice->value); ?>
            </div>
        </div>
        <div id="weekly-unity" class="tab-pane fade">
            <div class="row">
                <?php echo CHtml::label('پوستر همصدایی هفتگی',''); ?>
                <div>
                    <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                        'id' => 'weekly-unity-poster-uploader',
                        'name'=>'weekly_unity_poster',
                        'maxFiles' => 1,
                        'maxFileSize' => 2, //MB
                        'url' => $this->createUrl('/setting/manage/uploadPoster'),
                        'deleteUrl' => $this->createUrl('/setting/manage/deleteUploadPoster'),
                        'acceptedFiles' => '.jpg, .png, .gif',
                        'serverFiles' => $weeklyUnityPoster,
                        'onSuccess' => '
                            var responseObj = JSON.parse(res);
                            if(responseObj.status){
                                {serverName} = responseObj.fileName;
                                $(".poster-uploader-message").html("");
                            }
                            else{
                                $(".poster-uploader-message").html(responseObj.message);
                                this.removeFile(file);
                            }
                    ')); ?>
                    <div class="poster-uploader-message"></div>
                </div>
            </div>
        </div>
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
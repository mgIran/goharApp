<?php
/* @var $this ManageController */
/* @var $showEventMessage SiteOptions */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
	'تنظیمات',
);
?>
<h1>تنظیمات</h1>

<div class="form">

    <?php echo CHtml::beginForm(); ?>

    <div class="row">
        <?php echo CHtml::label('نمایش پیام به مخاطبین',''); ?>
        <div>
            - مراسمات
            <?php echo CHtml::textField('showEventMessage[0][0]', '', array('class'=>'event-message-field')) ?>
            الی
            <?php echo CHtml::textField('showEventMessage[0][1]', '', array('class'=>'event-message-field')) ?>
            روزه:
            <?php echo CHtml::textField('showEventMessage[0][2]', '', array('class'=>'event-message-field')) ?>
            ساعت قبل از شروع مراسم.
        </div>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Create'); ?>
    </div>

    <?php echo CHtml::endForm(); ?>

</div>

<?php Yii::app()->clientScript->registerCss('this-page-style', '
.event-message-field{
    display: inline-block;
    width: 30px;
}
');?>
<?php
/* @var $this ManageController */
/* @var $model Events */
/* @var $form CActiveForm */
/* @var $states array */
/* @var $categories array */
/* @var $poster array */
/* @var $maxMoreDays string */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'events-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php $this->renderPartial('//layouts/_flashMessage');?>

	<div class="row">
		<?php echo $form->labelEx($model,'subject1'); ?>
		<?php echo $form->textField($model,'subject1',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'subject1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'subject2'); ?>
		<?php echo $form->textField($model,'subject2',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'subject2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'conductor1'); ?>
		<?php echo $form->textField($model,'conductor1',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'conductor1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'conductor2'); ?>
		<?php echo $form->textField($model,'conductor2',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'conductor2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ceremony_public'); ?>
		<?php echo $form->checkBox($model, 'ceremony_public'); ?>
		<?php echo $form->error($model,'ceremony_public'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sexed_guest'); ?>
		<?php echo $form->dropDownList($model, 'sexed_guest', $model->sexLabels); ?>
		<?php echo $form->error($model,'sexed_guest'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'min_age_guests'); ?>
		<?php echo $form->textField($model,'min_age_guests',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'min_age_guests'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_age_guests'); ?>
		<?php echo $form->textField($model,'max_age_guests',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'max_age_guests'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_date_run'); ?>
		<?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
			'id'=>'start-date-run',
			'model'=>$model,
			'attribute'=>'start_date_run',
			'options'=>array(
				'format'=>'DD MMMM YYYY',
			),
		));?>
		<?php echo $form->error($model,'start_date_run'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'long_days_run'); ?>
		<?php echo $form->textField($model,'long_days_run',array('size'=>2,'maxlength'=>2)); ?>شبانه روز
		<?php echo $form->error($model,'long_days_run'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_time_run'); ?>
		<?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
			'id'=>'start-time-run',
			'model'=>$model,
			'attribute'=>'start_time_run',
			'options'=>array(
				'format'=>'HH:mm',
				'onlyTimePicker'=>true,
			),
		));?>
		<?php echo $form->error($model,'start_time_run'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'end_time_run'); ?>
		<?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
			'id'=>'end-time-run',
			'model'=>$model,
			'attribute'=>'end_time_run',
			'options'=>array(
				'format'=>'HH:mm',
				'onlyTimePicker'=>true,
			),
		));?>
		<?php echo $form->error($model,'end_time_run'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'more_days'); ?>
		<?php echo $form->textField($model,'more_days',array('size'=>2,'maxlength'=>2)); ?>
        <small>حداکثر تعداد روزهای اضافه تر از پیشفرض: <?php echo $maxMoreDays;?></small>
		<?php echo $form->error($model,'more_days'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'state_id'); ?>
		<?php echo $form->dropDownList($model,'state_id',$states,array('prompt'=>'لطفا انتخاب کنید')); ?>
		<?php echo $form->error($model,'state_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city_id'); ?>
		<?php if($model->isNewRecord):?>
        	<?php echo $form->dropDownList($model,'city_id',array(),array('prompt'=>'لطفا انتخاب کنید','disabled'=>true)); ?>
		<?php else:?>
        	<?php echo $form->dropDownList($model,'city_id',UsersPlaces::citiesByTown($model->state_id),array('prompt'=>'لطفا انتخاب کنید')); ?>
		<?php endif;?>
		<?php echo $form->error($model,'city_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'town'); ?>
		<?php echo $form->textField($model,'town',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'town'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'main_street'); ?>
		<?php echo $form->textField($model,'main_street',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'main_street'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'by_street'); ?>
		<?php echo $form->textField($model,'by_street',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'by_street'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'boulevard'); ?>
		<?php echo $form->textField($model,'boulevard',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'boulevard'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'afew_ways'); ?>
		<?php echo $form->textField($model,'afew_ways',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'afew_ways'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'squary'); ?>
		<?php echo $form->textField($model,'squary',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'squary'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bridge'); ?>
		<?php echo $form->textField($model,'bridge',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'bridge'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'quarter'); ?>
		<?php echo $form->textField($model,'quarter',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'quarter'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'area_code'); ?>
		<?php echo $form->textField($model,'area_code',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'area_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postal_code'); ?>
		<?php echo $form->textField($model,'postal_code',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'postal_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'complete_address'); ?>
		<?php echo $form->textArea($model,'complete_address',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'complete_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'complete_details'); ?>
		<?php echo $form->textArea($model,'complete_details',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'complete_details'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'reception'); ?>
		<?php echo $form->textField($model,'reception',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'reception'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'invitees'); ?>
        <div style="display: inline-block;vertical-align: top;">
        <?php if(is_null($model->invitees)):?>
            <div class="dynamic-field-container" data-name="Events[invitees][executer]" data-max="4">
                <div class="input-container">
                    <?php echo CHtml::textField('Events[invitees][executer][0]', '', array('class'=>'dynamic-field','placeholder'=>'مجری'));?>
                </div>
                <a href="#" class="add-dynamic-field"><i class="icon icon-plus"></i></a>
                <a href="#" class="remove-dynamic-field"><i class="icon icon-trash"></i></a>
            </div>
            <div class="dynamic-field-container" data-name="Events[invitees][reader]" data-max="4">
                <div class="input-container">
                    <?php echo CHtml::textField('Events[invitees][reader][0]', '', array('class'=>'dynamic-field','placeholder'=>'قاری'));?>
                </div>
                <a href="#" class="add-dynamic-field"><i class="icon icon-plus"></i></a>
                <a href="#" class="remove-dynamic-field"><i class="icon icon-trash"></i></a>
            </div>
            <div class="dynamic-field-container" data-name="Events[invitees][poet]" data-max="4">
                <div class="input-container">
                    <?php echo CHtml::textField('Events[invitees][poet][0]', '', array('class'=>'dynamic-field','placeholder'=>'شاعر'));?>
                </div>
                <a href="#" class="add-dynamic-field"><i class="icon icon-plus"></i></a>
                <a href="#" class="remove-dynamic-field"><i class="icon icon-trash"></i></a>
            </div>
            <div class="dynamic-field-container" data-name="Events[invitees][speaker]" data-max="4">
                <div class="input-container">
                    <?php echo CHtml::textField('Events[invitees][speaker][0]', '', array('class'=>'dynamic-field','placeholder'=>'سخنران'));?>
                </div>
                <a href="#" class="add-dynamic-field"><i class="icon icon-plus"></i></a>
                <a href="#" class="remove-dynamic-field"><i class="icon icon-trash"></i></a>
            </div>
            <div class="dynamic-field-container" data-name="Events[invitees][maddah]" data-max="4">
                <div class="input-container">
                    <?php echo CHtml::textField('Events[invitees][maddah][0]', '', array('class'=>'dynamic-field','placeholder'=>'مداح'));?>
                </div>
                <a href="#" class="add-dynamic-field"><i class="icon icon-plus"></i></a>
                <a href="#" class="remove-dynamic-field"><i class="icon icon-trash"></i></a>
            </div>
            <div class="dynamic-field-container" data-name="Events[invitees][singer]" data-max="4">
                <div class="input-container">
                    <?php echo CHtml::textField('Events[invitees][singer][0]', '', array('class'=>'dynamic-field','placeholder'=>'خواننده'));?>
                </div>
                <a href="#" class="add-dynamic-field"><i class="icon icon-plus"></i></a>
                <a href="#" class="remove-dynamic-field"><i class="icon icon-trash"></i></a>
            </div>
            <div class="dynamic-field-container" data-name="Events[invitees][team]" data-max="4">
                <div class="input-container">
                    <?php echo CHtml::textField('Events[invitees][team][0]', '', array('class'=>'dynamic-field','placeholder'=>'تیم/گروه'));?>
                </div>
                <a href="#" class="add-dynamic-field"><i class="icon icon-plus"></i></a>
                <a href="#" class="remove-dynamic-field"><i class="icon icon-trash"></i></a>
            </div>
            <div class="dynamic-field-container" data-name="Events[invitees][other]" data-max="4">
                <div class="input-container">
                    <?php echo CHtml::textField('Events[invitees][other][0]', '', array('class'=>'dynamic-field','placeholder'=>'سایر'));?>
                </div>
                <a href="#" class="add-dynamic-field"><i class="icon icon-plus"></i></a>
                <a href="#" class="remove-dynamic-field"><i class="icon icon-trash"></i></a>
            </div>
        <?php else:?>
            <?php foreach(CJSON::decode($model->invitees) as $key=>$value):?>
                <div class="dynamic-field-container" data-name="Events[invitees][<?php echo $key;?>]" data-max="4">
                    <div class="input-container">
                    <?php foreach($value as $item=>$input):?>
                        <?php echo CHtml::textField('Events[invitees]['.$key.']['.$item.']', $input, array('class'=>'dynamic-field','placeholder'=>$model->inviteesLabels[$key]));?>
                    <?php endforeach;?>
                    </div>
                    <a href="#" class="add-dynamic-field"><i class="icon icon-plus"></i></a>
                    <a href="#" class="remove-dynamic-field"><i class="icon icon-trash"></i></a>
                </div>
            <?php endforeach;?>
        <?php endif;?>
        </div>
		<?php echo $form->error($model,'invitees'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'activator_area_code'); ?>
		<?php echo $form->dropDownList($model,'activator_area_code', array('0'=>'غیر فعال', '1'=>'فعال')); ?>
		<?php echo $form->error($model,'activator_area_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'activator_postal_code'); ?>
        <?php echo $form->dropDownList($model,'activator_postal_code', array('0'=>'غیر فعال', '1'=>'فعال')); ?>
		<?php echo $form->error($model,'activator_postal_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'selectedCategories'); ?>
        <div style="display: inline-block;vertical-align: top;">
		    <?php echo $form->checkBoxList($model,'selectedCategories',$categories); ?>
        </div>
		<?php echo $form->error($model,'selectedCategories'); ?>
	</div>

    <div class="row">
		<?php echo $form->labelEx($model,'ceremony_poster'); ?>
        <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
            'id' => 'ceremony-poster-uploader',
            'model' => $model,
            'name' => 'ceremony_poster',
            'maxFiles' => 1,
            'maxFileSize' => 1, //MB
            'url' => $this->createUrl('/events/manage/upload'),
            'deleteUrl' => $this->createUrl('/events/manage/deleteUpload'),
            'acceptedFiles' => '.jpeg, .jpg, .png, .gif',
            'serverFiles' => $poster,
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
		<?php echo $form->error($model,'ceremony_poster'); ?>
	</div>

	<div class="row">
		<?php echo $form->error($model,'scenarioError'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'نمایش پیش فاکتور' : 'ذخیره', array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php Yii::app()->clientScript->registerScript('load-cities', "
$('#Events_state_id').on('change',function(){
    var town_id = $(this).val();
    $('#Events_city_id').val('');
    $('#Events_city_id').find('option').remove();
    $.ajax({
        url : '".$this->createUrl('/users/places/getCities')."/'+town_id,
        dataType:'json',
        success : function(data){
            $.each(data, function (i, item) {
                $('#Events_city_id').append($('<option>', {
                    value: i,
                    text : item
                }));
            });
            $('#Events_city_id').prop('disabled', false);
        }
    });
});
"); ?>
<?php Yii::app()->clientScript->registerScript('this-page-scripts', "
$('#Events_selectedCategories input[type=\"checkbox\"]').on('change',function(){
    var checkedLength=$('#Events_selectedCategories input[type=\"checkbox\"]:checked').length;
    if(checkedLength == 2)
        $('#Events_selectedCategories input[type=\"checkbox\"]:not(:checked)').prop('disabled',true);
    else if(checkedLength < 2)
        $('#Events_selectedCategories input[type=\"checkbox\"]:not(:checked)').prop('disabled',false);
});

if($('#Events_selectedCategories input[type=\"checkbox\"]:checked').length == 2)
    $('#Events_selectedCategories input[type=\"checkbox\"]:not(:checked)').prop('disabled',true);
"); ?>
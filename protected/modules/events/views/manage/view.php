<?php
/* @var $this EventsController */
/* @var $model Events */

$this->breadcrumbs=array(
	'مراسمات'=>array('admin'),
	$model->subject1,
);

$this->menu=array(
	array('label'=>'ثبت مراسم', 'url'=>array('create')),
	array('label'=>'ویرایش این مراسم', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف این مراسم', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'آیا از حذف این مراسم مطمئن هستید؟')),
	array('label'=>'لیست مراسمات', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->subject1; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'subject1',
		'subject2',
		'conductor1',
		'conductor2',
		array(
			'name'=>'sexed_guest',
			'value'=>$model->sexLabels[$model->sexed_guest]
		),
		'min_age_guests',
		'max_age_guests',
		array(
			'name'=>'start_date_run',
			'value'=>JalaliDate::date('d F Y', $model->start_date_run)
		),
		array(
			'name'=>'long_days_run',
			'value'=>$model->long_days_run.' شبانه روز'
		),
		array(
			'name'=>'start_time_run',
			'value'=>date('H:i', $model->start_time_run)
		),
		array(
			'name'=>'end_time_run',
			'value'=>date('H:i', $model->end_time_run)
		),
		'max_more_days',
		'more_days',
		array(
			'name'=>'state_id',
			'value'=>UsersPlaces::model()->findByPk($model->state_id)->title
		),
		array(
			'name'=>'city_id',
			'value'=>UsersPlaces::model()->findByPk($model->city_id)->title
		),
		'town',
		'main_street',
		'by_street',
		'boulevard',
		'afew_ways',
		'squary',
		'bridge',
		'quarter',
		'area_code',
		'postal_code',
		'complete_address',
		'complete_details',
		'reception',
		array(
			'name'=>'invitees',
			'value'=>$model->implodeInvitees('<br>'),
            'type'=>'raw'
		),
		array(
            'name'=>'activator_area_code',
            'value'=>$model->activator_area_code?'فعال':'غیرفعال'
        ),
        array(
            'name'=>'activator_postal_code',
            'value'=>$model->activator_postal_code?'فعال':'غیرفعال'
        ),
		array(
			'name'=>'ceremony_poster',
			'value'=>CHtml::image(Yii::app()->baseUrl.'/uploads/events/'.$model->ceremony_poster, '', array(
				'style'=>'max-width:200px;'
			)),
			'type'=>'raw'
		),
	),
));?>

<?php
/* @var $this ManageController */
/* @var $model Events */

$this->breadcrumbs=array(
	'مراسمات'=>array('admin'),
	'لیست',
);

$this->menu=array(
	array('label'=>'ثبت مراسم', 'url'=>array('create')),
);
?>

<h1>لیست مراسمات</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'events-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'subject1',
		'conductor1',
		array(
			'name'=>'sexed_guest',
			'value'=>'$data->sexLabels[$data->sexed_guest]',
			'filter'=>CHtml::activeDropDownList($model, 'sexed_guest', $model->sexLabels, array('prompt'=>'همه'))
		),
		/*
		'min_age_guests',
		'max_age_guests',
		'start_date_run',
		'long_days_run',
		'start_time_run',
		'end_time_run',
		'max_more_days',
		'more_days',
		'state_id',
		'city_id',
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
		'invitees',
		'activator_area_code',
		'activator_postal_code',
		'ceremony_poster',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

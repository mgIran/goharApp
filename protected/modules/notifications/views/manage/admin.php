<?php
/* @var $this NotificationsManageController */
/* @var $model Notifications */

$this->breadcrumbs=array(
	'اطلاعیه ها'=>array('admin'),
	'لیست',
);

$this->menu=array(
	array('label'=>'ارسال اطلاعیه', 'url'=>'create'),
);
?>

<h1>لیست اطلاعیه ها</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'notifications-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'subject',
		array(
			'name'=>'send_date',
			'value'=>'JalaliDate::date("d F Y - H:i", $data->send_date)',
			'filter'=>false
		),
		array(
			'name'=>'expire_date',
			'value'=>'JalaliDate::date("d F Y - H:i", $data->expire_date)',
			'filter'=>false
		),
		array(
			'name'=>'status',
			'value'=>'$data->statusLabels[$data->status]',
			'filter'=>CHtml::activeDropDownList($model, "status", $model->statusLabels, array('prompt'=>'همه'))
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

<?php
/* @var $this UnityManageController */
/* @var $model Unity */

$this->breadcrumbs=array(
	'همصدایی'=>array('admin'),
	'مدیریت',
);

$this->menu=array(
	array('label'=>'لیست همصدایی ها', 'url'=>array('admin')),
	array('label'=>'ثبت همصدایی', 'url'=>array('create')),
);
?>

<h1>لیست همصدایی ها</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'unity-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'subject',
		array(
			'name'=>'date',
			'value'=>'JalaliDate::date("d F Y - H:i", $data->date)',
			'filter'=>false
		),
        array(
			'name'=>'notices_date',
			'value'=>'JalaliDate::date("d F Y", $data->notices_date)',
			'filter'=>false
		),
		array(
			'name'=>'receiver_count',
			'filter'=>false
		),
		array(
			'name'=>'status',
			'value'=>'$data->statusLabels[$data->getStatus()]',
			'filter'=>CHtml::activeDropDownList($model, "status", $model->statusLabels, array('prompt'=>'همه'))
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

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
	'id'=>'confirmed-events-grid',
	'dataProvider'=>$model->search('status = 1'),
	'filter'=>$model,
	'columns'=>array(
		'subject1',
		'conductor1',
		array(
			'name'=>'sexed_guest',
			'value'=>'$data->sexLabels[$data->sexed_guest]',
			'filter'=>CHtml::activeDropDownList($model, 'sexed_guest', $model->sexLabels, array('prompt'=>'همه'))
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{bill} {view} {update} {delete}',
			'buttons'=>array(
				'bill'=>array(
					'imageUrl'=>Yii::app()->theme->baseUrl."/img/bill.png",
					'url'=>'Yii::app()->createUrl("/events/manage/bill/".$data->id)'
				)
			)
		),
	),
)); ?>

<hr>
<h4>لیست مراسمات تایید نشده</h4>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'events-grid',
	'dataProvider'=>$model->search('status = 0'),
	'filter'=>$model,
	'columns'=>array(
		'subject1',
		'conductor1',
		array(
			'name'=>'sexed_guest',
			'value'=>'$data->sexLabels[$data->sexed_guest]',
			'filter'=>CHtml::activeDropDownList($model, 'sexed_guest', $model->sexLabels, array('prompt'=>'همه'))
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{bill} {view} {update} {delete}',
			'buttons'=>array(
				'bill'=>array(
					'imageUrl'=>Yii::app()->theme->baseUrl."/img/bill.png",
					'url'=>'Yii::app()->createUrl("/events/manage/bill/".$data->id)'
				)
			)
		),
	),
)); ?>

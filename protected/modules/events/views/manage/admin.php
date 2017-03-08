<?php
/* @var $this ManageController */
/* @var $model Events */
/* @var $states array */

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
			'name'=>'state_id',
			'value'=>'UsersPlaces::model()->findByPk($data->state_id)->title',
			'filter'=>CHtml::activeDropDownList($model, 'state_id', $states, array('prompt'=>'همه'))
		),
		array(
			'name'=>'creator_mobile',
			'value'=>'($data->creator_type != "admin")?Users::model()->findByPk($data->creator_id)->mobile:"-"',
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
            'name'=>'state_id',
            'value'=>'UsersPlaces::model()->findByPk($data->state_id)->title',
            'filter'=>CHtml::activeDropDownList($model, 'state_id', $states, array('prompt'=>'همه'))
        ),
        array(
            'name'=>'creator_mobile',
            'value'=>'($data->creator_type != "admin")?Users::model()->findByPk($data->creator_id)->mobile:"-"',
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

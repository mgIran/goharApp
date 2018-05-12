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
	'dataProvider'=>$model->search('status != '.Events::STATUS_PENDING),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'subject1',
			'value'=>'$data->subject1." - ".$data->subject2'
		),
		array(
			'name'=>'conductor1',
			'value'=>'$data->conductor1." - ".$data->conductor2'
		),
		array(
			'name'=>'state_id',
			'value'=>'UsersPlaces::model()->findByPk($data->state_id)->title',
			'filter'=>CHtml::activeDropDownList($model, 'state_id', $states, array('prompt'=>'همه'))
		),
		array(
			'name'=>'creator_mobile',
			'value'=>'($data->creator_type != "admin")?Users::model()->findByPk($data->creator_id)->mobile:($data->user_mobile?$data->user_mobile." - ":"")."مدیر"',
		),
		array(
			'header'=>'کد رهگیری',
			'value'=>'',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{bill} {view} {update} {delete}',
			'buttons'=>array(
				'bill'=>array(
					'imageUrl'=>Yii::app()->theme->baseUrl."/img/bill.png",
					'url'=>'Yii::app()->createUrl("/events/manage/bill/".$data->id)',
					'label'=>'فاکتور'
				)
			)
		),
	),
)); ?>

<hr>
<h4>لیست مراسمات تایید نشده</h4>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'events-grid',
	'dataProvider'=>$model->search('status = '.Events::STATUS_PENDING),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'subject1',
			'value'=>'$data->subject1." - ".$data->subject2'
		),
        array(
            'name'=>'conductor1',
            'value'=>'$data->conductor1." - ".$data->conductor2'
        ),
        array(
            'name'=>'state_id',
            'value'=>'UsersPlaces::model()->findByPk($data->state_id)->title',
            'filter'=>CHtml::activeDropDownList($model, 'state_id', $states, array('prompt'=>'همه'))
        ),
        array(
            'name'=>'creator_mobile',
			'value'=>'($data->creator_type != "admin")?Users::model()->findByPk($data->creator_id)->mobile:($data->user_mobile?$data->user_mobile." - ":"")."مدیر"',
        ),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{bill} {view} {update} {delete}',
			'buttons'=>array(
				'bill'=>array(
					'imageUrl'=>Yii::app()->theme->baseUrl."/img/bill.png",
					'url'=>'Yii::app()->createUrl("/events/manage/confirmBill/".$data->id)',
					'label'=>'پیش فاکتور'
				)
			)
		),
	),
)); ?>

<?php
/* @var $this NotificationsManageController */
/* @var $model Notifications */

$this->breadcrumbs=array(
	'اطلاعیه ها'=>array('index'),
	$model->subject,
);

$this->menu=array(
	array('label'=>'ارسال اطلاعیه', 'url'=>array('create')),
	array('label'=>'ویرایش این اطلاعیه', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف این اطلاعیه', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'آیا از حذف این اطلاعیه مطمئن هستید؟')),
	array('label'=>'لیست اطلاعیه ها', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->subject; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'subject',
		array(
			'name'=>'send_date',
			'value'=>JalaliDate::date('d F Y - H:i', $model->send_date)
		),
		array(
			'name'=>'expire_date',
			'value'=>JalaliDate::date('d F Y - H:i', $model->expire_date)
		),
		'content',
		array(
			'name'=>'status',
			'value'=>$model->statusLabels[$model->status]
		),
		array(
			'name'=>'poster',
			'value'=>CHtml::image(Yii::app()->baseUrl.'/uploads/notifications/'.$model->poster, '', array('style'=>'max-width:200px;max-height:200px;')),
			'type'=>'raw'
		),
		'visit',
	),
)); ?>

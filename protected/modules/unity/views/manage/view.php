<?php
/* @var $this UnityManageController */
/* @var $model Unity */

$this->breadcrumbs=array(
	'همصدایی'=>array('admin'),
	$model->subject,
);

$this->menu=array(
	array('label'=>'ثبت همصدایی', 'url'=>array('create')),
	array('label'=>'ویرایش "'.$model->subject.'"', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف "'.$model->subject.'"', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'آیا از حذف این آیتم مطمئن هستید؟')),
	array('label'=>'لیست همصدایی ها', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->subject; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'subject',
		array(
			'name'=>'date',
			'value'=>JalaliDate::date('d F Y - H:i', $model->date)
		),
		array(
			'name'=>'notices_date',
			'value'=>JalaliDate::date('d F Y', $model->notices_date)
		),
		'content',
		'receiver_count',
		array(
			'name'=>'poster',
			'value'=>CHtml::image(Yii::app()->baseUrl.'/uploads/unity/'.$model->poster, '', array('style'=>'max-width:200px;max-height:200px;')),
			'type'=>'raw'
		),
	),
)); ?>

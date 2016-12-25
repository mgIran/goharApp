<?php
/* @var $this NotificationsManageController */
/* @var $model Notifications */
/* @var $poster array */

$this->breadcrumbs=array(
	'اطلاعیه ها'=>array('admin'),
	$model->subject=>array('view','id'=>$model->id),
	'ویرایش',
);

$this->menu=array(
	array('label'=>'ارسال اطلاعیه', 'url'=>array('create')),
	array('label'=>'نمایش این اطلاعیه', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'لیست اطلاعیه ها', 'url'=>array('admin')),
);
?>

<h1>ویرایش "<?php echo $model->subject; ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'poster'=>$poster)); ?>
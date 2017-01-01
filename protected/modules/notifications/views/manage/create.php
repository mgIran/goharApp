<?php
/* @var $this NotificationsManageController */
/* @var $model Notifications */
/* @var $poster array */

$this->breadcrumbs=array(
	'اطلاعیه ها'=>array('admin'),
	'ارسال',
);

$this->menu=array(
	array('label'=>'لیست اطلاعیه ها', 'url'=>array('admin')),
);
?>

<h1>ارسال اطلاعیه</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'poster'=>$poster)); ?>
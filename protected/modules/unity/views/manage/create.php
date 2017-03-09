<?php
/* @var $this UnityManageController */
/* @var $model Unity */
/* @var $poster array */

$this->breadcrumbs=array(
	'همصدایی'=>array('admin'),
	'ثبت',
);

$this->menu=array(
	array('label'=>'لیست همصدایی ها', 'url'=>array('admin')),
);
?>

<h1>ثبت همصدایی</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'poster'=>$poster)); ?>
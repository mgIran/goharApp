<?php
/* @var $this ManageController */
/* @var $model Events */
/* @var $states array */
/* @var $categories array */
/* @var $poster array */
/* @var $maxMoreDays string */

$this->breadcrumbs=array(
	'مراسمات'=>array('admin'),
	'ثبت مراسم',
);

$this->menu=array(
	array('label'=>'لیست مراسمات', 'url'=>array('admin')),
);
?>

<h1>ثبت مراسم</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'states'=>$states, 'categories'=>$categories, 'poster'=>$poster, 'maxMoreDays'=>$maxMoreDays)); ?>
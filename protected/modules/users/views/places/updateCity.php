<?php
/* @var $this PlacesController */
/* @var $model Places */

$this->breadcrumbs=array(
	'Places',
	$model->title=>array('view','id'=>$model->id),
	'UsersPlaces',
);

$this->menu=array(
	array('label'=>'افزودن شهر', 'url'=>array('createCity')),
	array('label'=>'مدیریت شهر ها', 'url'=>array('adminCities')),
);
?>

<?php $this->renderPartial('_form_city', array('model'=>$model,'title'=>'ویرایش شهر')); ?>
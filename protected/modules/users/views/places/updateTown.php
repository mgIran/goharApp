<?php
/* @var $this PlacesController */
/* @var $model Places */

$this->breadcrumbs=array(
	'UsersPlaces',
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'افزودن استان', 'url'=>array('createTown')),
	array('label'=>'مدیریت استان ها', 'url'=>array('adminTowns')),
);
?>

<?php $this->renderPartial('_form_town', array('model'=>$model,'title'=>'ویرایش استان')); ?>
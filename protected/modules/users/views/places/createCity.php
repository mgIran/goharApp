<?php
/* @var $this PlacesController */
/* @var $model Places */

$this->breadcrumbs=array(
	'UsersPlaces',
	'Create',
);

$this->menu=array(
	array('label'=>'مدیریت شهر ها', 'url'=>array('adminCities')),
    array('label'=>'افزودن استان', 'url'=>array('createTown')),
);
?>

<?php $this->renderPartial('_form_city', array('model'=>$model,'title'=>'افزودن شهر')); ?>
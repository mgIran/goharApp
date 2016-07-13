<?php
/* @var $this PlacesController */
/* @var $model Places */

$this->breadcrumbs=array(
	'UsersPlaces'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'مدیریت استان ها', 'url'=>array('adminTowns')),
    array('label'=>'افزودن شهر', 'url'=>array('createCity')),
);
?>

<?php $this->renderPartial('_form_town', array('model'=>$model,'title'=>'افزودن استان')); ?>
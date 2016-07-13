<?php
/* @var $this PlacesController */
/* @var $model Places */

$this->breadcrumbs=array(
	'UsersPlaces'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'مدیریت مکان ها', 'url'=>array('admin')),
);
?>

<h1>
    افزودن مکان
</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
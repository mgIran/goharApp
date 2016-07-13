<?php
/* @var $this PlacesController */
/* @var $model Places */

$this->breadcrumbs=array(
	'UsersPlaces'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'افزودن مکان', 'url'=>array('create')),
	array('label'=>'مدیریت مکان ها', 'url'=>array('admin')),
);
?>

<h1>ویرایش مکان <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
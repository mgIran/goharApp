<?php
/* @var $this TicketsCategoriesController */
/* @var $model TicketsCategories */

$this->breadcrumbs=array(
	'Tickets Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'مدیریت بخش ها', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
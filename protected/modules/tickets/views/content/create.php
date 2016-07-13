<?php
/* @var $this TicketsContentController */
/* @var $model TicketsContent */

$this->breadcrumbs=array(
	'Tickets Contents'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TicketsContent', 'url'=>array('index')),
	array('label'=>'Manage TicketsContent', 'url'=>array('admin')),
);
?>

<h1>Create TicketsContent</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
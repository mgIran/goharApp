<?php
/* @var $this TicketsContentController */
/* @var $model TicketsContent */

$this->breadcrumbs=array(
	'Tickets Contents'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TicketsContent', 'url'=>array('index')),
	array('label'=>'Create TicketsContent', 'url'=>array('create')),
	array('label'=>'View TicketsContent', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TicketsContent', 'url'=>array('admin')),
);
?>

<h1>Update TicketsContent <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
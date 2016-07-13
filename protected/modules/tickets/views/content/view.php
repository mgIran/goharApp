<?php
/* @var $this TicketsContentController */
/* @var $model TicketsContent */

$this->breadcrumbs=array(
	'Tickets Contents'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List TicketsContent', 'url'=>array('index')),
	array('label'=>'Create TicketsContent', 'url'=>array('create')),
	array('label'=>'Update TicketsContent', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TicketsContent', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TicketsContent', 'url'=>array('admin')),
);
?>

<h1>View TicketsContent #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'ticket_id',
		'text',
		'file',
		'admin_id',
		'date',
	),
)); ?>
